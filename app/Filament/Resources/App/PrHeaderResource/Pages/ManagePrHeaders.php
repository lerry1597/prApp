<?php

namespace App\Filament\Resources\App\PrHeaderResource\Pages;

use App\Constants\PrStatusConstant;
use App\Filament\Resources\App\PrHeaderResource;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemLog;
use App\Models\PrHeader;
use App\Models\PrHistory;
use App\Models\PrLog;
use App\Service\ApprovalFlowService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ManagePrHeaders extends Page
{
    use WithPagination;

    protected static string $resource = PrHeaderResource::class;

    protected string $view = 'filament.app.pages.manage-pr-headers';

    // ── Search & Filter ──
    public string $search = '';
    public string $statusFilter = '';

    // ── Modal detail / approval ──
    public bool $showModal = false;
    public ?int $selectedId = null;
    public ?PrHeader $selectedPr = null;

    // ── Approval form fields ──
    public array $itemQuantities = [];
    public string $approvalError = '';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function getPrListProperty()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        return PrHeader::query()
            ->whereIn('current_role_id', $userRoleIds)
            ->whereIn('pr_status', [
                PrStatusConstant::WAITING_APPROVAL,
                PrStatusConstant::APPROVED,
            ])
            ->with(['requester', 'detail.vessel', 'currentRole'])
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('pr_number', 'like', "%{$this->search}%")
                        ->orWhereHas('requester', fn($r) => $r->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('detail', fn($d) => $d->where('needs', 'like', "%{$this->search}%")
                            ->orWhereHas('vessel', fn($v) => $v->where('name', 'like', "%{$this->search}%")));
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('pr_status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getStatsProperty(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        $base = PrHeader::query()
            ->whereIn('current_role_id', $userRoleIds)
            ->whereIn('pr_status', [PrStatusConstant::WAITING_APPROVAL, PrStatusConstant::APPROVED]);

        return [
            'total'   => (clone $base)->count(),
            'waiting' => (clone $base)->where('pr_status', PrStatusConstant::WAITING_APPROVAL)->count(),
            'done'    => (clone $base)->where('pr_status', PrStatusConstant::APPROVED)->count(),
        ];
    }

    public function openModal(int $id): void
    {
        $this->selectedId    = $id;
        $this->selectedPr    = PrHeader::with([
            'requester', 'detail.vessel', 'currentRole', 'currentStep',
        ])->findOrFail($id);

        // Prefill quantities
        $items = $this->selectedPr->items()->with('itemCategory')->orderBy('id')->get();
        $this->itemQuantities = $items->mapWithKeys(fn($item) => [$item->id => $item->quantity])->toArray();

        $this->approvalError = '';
        $this->showModal     = true;
    }

    public function closeModal(): void
    {
        $this->showModal    = false;
        $this->selectedPr   = null;
        $this->selectedId   = null;
        $this->itemQuantities = [];
        $this->approvalError  = '';
    }

    public function getSelectedItemsProperty()
    {
        if (! $this->selectedPr) return collect();
        return $this->selectedPr->items()->with('itemCategory')->orderBy('id')->get();
    }

    public function submitApproval(): void
    {
        $this->approvalError = '';

        /** @var \App\Models\User|null $user */
        $user   = Auth::user();
        $header = $this->selectedPr?->fresh(['detail', 'currentStep', 'approvalWorkflow']);

        if (! $user || ! $header || ! $header->detail) {
            $this->approvalError = 'Data PR tidak lengkap atau tidak ditemukan.';
            return;
        }

        $flowContext = app(ApprovalFlowService::class)->resolveApprovalContext($user, $header);
        if (! $flowContext['ok']) {
            $this->approvalError = $flowContext['title'] . ': ' . $flowContext['message'];
            return;
        }

        $currentStep = $flowContext['currentStep'];
        $nextStep    = $flowContext['nextStep'];
        $items       = $header->items()->orderBy('id')->get();

        if ($items->isEmpty()) {
            $this->approvalError = 'Tidak ada item yang dapat diproses.';
            return;
        }

        // Update quantities from form
        foreach ($items as $item) {
            $qty = $this->itemQuantities[$item->id] ?? $item->quantity;
            if (! is_numeric($qty) || $qty < 1) {
                $this->approvalError = "Jumlah untuk item \"{$item->type}\" harus minimal 1.";
                return;
            }
            $item->update(['quantity' => $qty]);
        }

        $now     = now();
        $batchId = $header->batch_id;

        try {
            DB::transaction(function () use ($batchId, $currentStep, $header, $nextStep, $now, $user): void {
                $isFinalStep = $nextStep === null;

                DB::table('pr_headers')->where('id', $header->id)->update([
                    'pr_status'       => PrStatusConstant::APPROVED,
                    'current_role_id' => $isFinalStep ? null : $nextStep->role_id,
                    'current_step_id' => $isFinalStep ? null : $nextStep->id,
                    'approver_id'     => $user->id,
                    'approved_at'     => $now,
                    'updated_at'      => $now,
                ]);

                $header->refresh()->loadMissing('detail');
                $detail      = $header->detail;
                $latestItems = $header->items()->orderBy('id')->get();

                $requestedItemsPayload = [
                    'items' => $latestItems->values()->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'item_category_id' => $item->item_category_id,
                            'type' => $item->type,
                            'size' => $item->size,
                            'quantity' => (float) $item->quantity,
                            'unit' => $item->unit,
                            'remaining' => (float) $item->remaining,
                        ];
                    })->toArray(),
                    'next_step_id'      => $header->current_step_id,
                    'next_role_id'      => $header->current_role_id,
                ];

                PrLog::create([
                    'batch_id'             => $batchId,
                    'action'               => 'APPROVE',
                    'status'               => 'SUCCESS',
                    'notes'                => 'Approver submitted approval.',
                    'user_id'              => $user->id,
                    'role_id'              => $currentStep->role_id,
                    'pr_header_id'         => $header->id,
                    'pr_number'            => $header->pr_number,
                    'pr_status'            => $header->pr_status,
                    'requester_id'         => $header->requester_id,
                    'department_id'        => $header->department_id,
                    'approver_id'          => $header->approver_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id'      => $header->current_role_id,
                    'current_step_id'      => $header->current_step_id,
                    'header_description'   => $header->description,
                    'priority'             => $detail?->priority,
                    'document_no'          => $detail?->document_no,
                    'title'                => $detail?->title,
                    'issue_date'           => $detail?->issue_date,
                    'rev_no'               => $detail?->rev_no,
                    'ref_date'             => $detail?->ref_date,
                    'document_type'        => $detail?->document_type,
                    'no'                   => $detail?->no,
                    'needs'                => $detail?->needs,
                    'vessel_id'            => $detail?->vessel_id,
                    'request_date'         => $detail?->request_date,
                    'required_date'        => $detail?->required_date,
                    'expired_date'         => $detail?->expired_date,
                    'detail_description'   => $detail?->description,
                    'payload'              => $requestedItemsPayload,
                ]);

                PrHistory::create([
                    'batch_id'             => $batchId,
                    'pr_header_id'         => $header->id,
                    'pr_number'            => $header->pr_number,
                    'pr_status'            => $header->pr_status,
                    'requester_id'         => $header->requester_id,
                    'department_id'        => $header->department_id,
                    'approver_id'          => $header->approver_id,
                    'approval_workflow_id' => $header->approval_workflow_id,
                    'current_role_id'      => $header->current_role_id,
                    'current_step_id'      => $header->current_step_id,
                    'header_description'   => $header->description,
                    'priority'             => $detail?->priority,
                    'document_no'          => $detail?->document_no,
                    'title'                => $detail?->title,
                    'issue_date'           => $detail?->issue_date,
                    'rev_no'               => $detail?->rev_no,
                    'ref_date'             => $detail?->ref_date,
                    'document_type'        => $detail?->document_type,
                    'no'                   => $detail?->no,
                    'needs'                => $detail?->needs,
                    'vessel_id'            => $detail?->vessel_id,
                    'request_date'         => $detail?->request_date,
                    'required_date'        => $detail?->required_date,
                    'expired_date'         => $detail?->expired_date,
                    'detail_description'   => $detail?->description,
                    'payload'              => $requestedItemsPayload,
                ]);

                foreach ($latestItems as $item) {
                    $snap = [
                        'batch_id'         => $batchId,
                        'pr_detail_id'     => $item->pr_detail_id,
                        'vessel_id'        => $item->vessel_id,
                        'item_category_id' => $item->item_category_id,
                        'no'               => $item->no,
                        'type'             => $item->type,
                        'size'             => $item->size,
                        'description'      => $item->description,
                        'quantity'         => $item->quantity,
                        'unit'             => $item->unit,
                        'remaining'        => $item->remaining,
                        'step_order'       => $currentStep->step_order,
                    ];
                    ItemLog::create($snap);
                    ItemHistory::create($snap);
                }
            });
        } catch (\Throwable $e) {
            $this->approvalError = 'Gagal menyimpan: ' . $e->getMessage();
            return;
        }

        $this->closeModal();

        Notification::make()
            ->success()
            ->title('Approval Berhasil')
            ->body('PR berhasil diproses ke tahap berikutnya.')
            ->send();
    }

    public function resetFilters(): void
    {
        $this->search       = '';
        $this->statusFilter = '';
        $this->resetPage();
    }
}
