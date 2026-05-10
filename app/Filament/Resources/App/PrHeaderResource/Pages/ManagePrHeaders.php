<?php

namespace App\Filament\Resources\App\PrHeaderResource\Pages;

use App\Constants\PrStatusConstant;
use App\Filament\Pages\App\ApprovedPrList;
use App\Filament\Resources\App\PrHeaderResource;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemHistory;
use App\Models\ItemLog;
use App\Models\PrHeader;
use App\Models\PrHistory;
use App\Models\PrLog;
use App\Service\ApprovalFlowService;
use App\Service\PrStatusResolverService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ManagePrHeaders extends Page
{
    use WithPagination;

    protected static string $resource = PrHeaderResource::class;

    protected static ?string $title = 'Daftar pengajuan barang';

    protected string $view = 'filament.app.pages.manage-pr-headers';

    // ── Search & Filter ──
    public string $search = '';
    public string $statusFilter = '';
    public string $needsFilter = '';
    public string $sortColumn = 'request_date';
    public string $sortDirection = 'desc';
    public array $matchedItemHints = [];
    public int $initialTake = 10;
    public int $loadStep = 10;
    public int $visibleCount = 10;
    public bool $hasMoreRows = false;

    // ── Modal detail / approval ──
    public bool $showModal = false;
    public ?int $selectedId = null;
    public ?PrHeader $selectedPr = null;

    // ── Approval form fields ──
    public array $itemCategoryIds = [];
    public array $itemTypes = [];
    public array $itemSizes = [];
    public array $itemQuantities = [];
    public array $itemUnits = [];
    public array $itemRemainings = [];
    public array $itemPriorities = [];
    public array $itemDescriptions = [];
    public string $approvalError = '';

    protected $queryString = ['search', 'statusFilter', 'needsFilter', 'sortColumn', 'sortDirection'];

    public function getHeading(): string
    {
        return 'Daftar pengajuan barang';
    }

    public function getBreadcrumb(): string
    {
        return 'Daftar pengajuan barang';
    }

    public function updatingSearch(): void
    {
        $this->visibleCount = $this->initialTake;
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->visibleCount = $this->initialTake;
        $this->resetPage();
    }

    public function updatingNeedsFilter(): void
    {
        $this->visibleCount = $this->initialTake;
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        $allowedColumns = ['document_no', 'vessel_name', 'request_date', 'status', 'needs'];
        if (! in_array($column, $allowedColumns, true)) {
            return;
        }

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }

        $this->visibleCount = $this->initialTake;
    }

    public function loadMore(): void
    {
        if (! $this->hasMoreRows) {
            return;
        }

        $this->visibleCount += $this->loadStep;
    }

    public function getPrListProperty()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        $sortMap = [
            'document_no' => 'pd.document_no',
            'vessel_name' => 'vs.name',
            'request_date' => 'pd.request_date',
            'status' => 'pr_headers.pr_status',
            'needs' => 'pd.needs',
        ];

        $sortColumn = $sortMap[$this->sortColumn] ?? 'pd.request_date';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        $query = PrHeader::query()
            ->leftJoin('pr_details as pd', 'pd.pr_header_id', '=', 'pr_headers.id')
            ->leftJoin('vessels as vs', 'vs.id', '=', 'pd.vessel_id')
            ->select('pr_headers.*')
            ->whereIn('current_role_id', $userRoleIds)
            ->whereIn('pr_status', [
                PrStatusConstant::WAITING_APPROVAL,
                PrStatusConstant::APPROVED,
                PrStatusConstant::PARTIALLY_APPROVED,
            ])
            ->with(['requester', 'detail.vessel', 'currentRole'])
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner
                        ->where('pd.document_no', 'like', "%{$this->search}%")
                        ->orWhere('vs.name', 'like', "%{$this->search}%")
                        ->orWhereHas('items', fn($items) => $items->where('type', 'like', "%{$this->search}%"));
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('pr_status', $this->statusFilter))
            ->when($this->needsFilter !== '', function ($q): void {
                $q->whereRaw('LOWER(pd.needs) = ?', [strtolower($this->needsFilter)]);
            })
            ->orderBy($sortColumn, $sortDirection)
            ->orderBy('pr_headers.id', 'desc');

        $rows = $query
            ->limit($this->visibleCount + 1)
            ->get();

        $this->hasMoreRows = $rows->count() > $this->visibleCount;
        $list = $rows->take($this->visibleCount)->values();

        $this->matchedItemHints = [];
        if ($this->search !== '') {
            $headerIds = $list->pluck('id')->filter()->unique()->values();

            if ($headerIds->isNotEmpty()) {
                $search = '%' . $this->search . '%';
                $this->matchedItemHints = Item::query()
                    ->select('pr_details.pr_header_id', 'items.type')
                    ->join('pr_details', 'pr_details.id', '=', 'items.pr_detail_id')
                    ->whereIn('pr_details.pr_header_id', $headerIds)
                    ->where('items.type', 'like', $search)
                    ->get()
                    ->groupBy('pr_header_id')
                    ->map(fn($rows): array => $rows->pluck('type')->unique()->take(3)->values()->all())
                    ->toArray();
            }
        }

        return $list;
    }

    public function getStatsProperty(): array
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRoleIds = $user->roles()->pluck('roles.id')->toArray();

        $base = PrHeader::query()
            ->whereIn('current_role_id', $userRoleIds)
            ->whereIn('pr_status', [
                PrStatusConstant::WAITING_APPROVAL,
                PrStatusConstant::APPROVED,
                PrStatusConstant::PARTIALLY_APPROVED,
            ]);

        return [
            'total'   => (clone $base)->count(),
            'waiting' => (clone $base)->where('pr_status', PrStatusConstant::WAITING_APPROVAL)->count(),
            'done'    => (clone $base)->whereIn('pr_status', [PrStatusConstant::APPROVED, PrStatusConstant::PARTIALLY_APPROVED])->count(),
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
        $this->itemCategoryIds = $items->mapWithKeys(fn($item) => [$item->id => $item->item_category_id])->toArray();
        $this->itemTypes = $items->mapWithKeys(fn($item) => [$item->id => $item->type])->toArray();
        $this->itemSizes = $items->mapWithKeys(fn($item) => [$item->id => $item->size])->toArray();
        $this->itemQuantities = $items->mapWithKeys(fn($item) => [$item->id => $item->quantity_approve ?? $item->quantity])->toArray();
        $this->itemUnits = $items->mapWithKeys(fn($item) => [$item->id => $item->unit])->toArray();
        $this->itemRemainings = $items->mapWithKeys(fn($item) => [$item->id => $item->remaining])->toArray();
        $this->itemPriorities = $items->mapWithKeys(fn($item) => [$item->id => $item->item_priority ?? 'Rutin'])->toArray();
        $this->itemDescriptions = $items->mapWithKeys(fn($item) => [$item->id => $item->description ?? ''])->toArray();

        $this->approvalError = '';
        $this->showModal     = true;
    }

    public function closeModal(): void
    {
        $this->showModal    = false;
        $this->selectedPr   = null;
        $this->selectedId   = null;
        $this->itemCategoryIds = [];
        $this->itemTypes = [];
        $this->itemSizes = [];
        $this->itemQuantities = [];
        $this->itemUnits = [];
        $this->itemRemainings = [];
        $this->itemPriorities = [];
        $this->itemDescriptions = [];
        $this->approvalError  = '';
    }

    public function getItemCategoryOptionsProperty(): array
    {
        return ItemCategory::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getSelectedItemsProperty()
    {
        if (! $this->selectedPr) return collect();
        return $this->selectedPr->items()->with('itemCategory')->orderBy('id')->get();
    }

    public function deleteItem(int $itemId): void
    {
        if (! $this->selectedPr) {
            return;
        }

        $item = $this->selectedPr->items()->whereKey($itemId)->first();
        if (! $item) {
            return;
        }

        $item->delete();

        unset($this->itemCategoryIds[$itemId]);
        unset($this->itemTypes[$itemId]);
        unset($this->itemSizes[$itemId]);
        unset($this->itemQuantities[$itemId]);
        unset($this->itemUnits[$itemId]);
        unset($this->itemRemainings[$itemId]);
        unset($this->itemPriorities[$itemId]);

        $this->selectedPr = $this->selectedPr->fresh(['requester', 'detail.vessel', 'currentRole', 'currentStep']);
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
        $allItems    = $header->items()->withTrashed()->orderBy('id')->get();
        $items       = $header->items()->orderBy('id')->get();

        if ($allItems->isEmpty()) {
            $this->approvalError = 'Tidak ada item yang dapat diproses.';
            return;
        }

        // Update item values from form
        foreach ($items as $item) {
            $type = trim((string) ($this->itemTypes[$item->id] ?? $item->type));
            $size = trim((string) ($this->itemSizes[$item->id] ?? $item->size));
            $unit = trim((string) ($this->itemUnits[$item->id] ?? $item->unit));
            $priority = trim((string) ($this->itemPriorities[$item->id] ?? $item->item_priority ?? 'Rutin'));
            $qtyApprove = $this->itemQuantities[$item->id] ?? $item->quantity;
            $remaining = $this->itemRemainings[$item->id] ?? $item->remaining;
            $categoryId = $this->itemCategoryIds[$item->id] ?? $item->item_category_id;

            if ($type === '') {
                $this->approvalError = 'Nama barang tidak boleh kosong.';
                return;
            }

            if (! is_numeric($qtyApprove) || (float) $qtyApprove < 0) {
                $this->approvalError = "Jumlah disetujui untuk item \"{$item->type}\" harus berupa angka minimal 0.";
                return;
            }

            if (! is_numeric($remaining) || (float) $remaining < 0) {
                $this->approvalError = "Sisa untuk item \"{$item->type}\" harus berupa angka minimal 0.";
                return;
            }

            $itemStatus = app(PrStatusResolverService::class)->resolveItemStatusFromValues(
                quantity: $item->quantity,
                quantityApprove: $qtyApprove,
            );

            $item->update([
                'item_category_id' => $categoryId,
                'type' => $type,
                'size' => $size,
                'quantity_approve' => (float) $qtyApprove,
                'unit' => $unit,
                'remaining' => (float) $remaining,
                'item_priority' => $priority,
                'status' => $itemStatus,
                'description' => trim((string) ($this->itemDescriptions[$item->id] ?? '')),
            ]);
        }

        $now     = now();
        $batchId = $header->batch_id;

        try {
            DB::transaction(function () use ($batchId, $currentStep, $header, $nextStep, $now, $user): void {
                $isFinalStep = $nextStep === null;

                $statusItems = $header->items()->withTrashed()->orderBy('id')->get();
                $nextStatus = app(PrStatusResolverService::class)->resolvePrStatusFromItems($statusItems);

                DB::table('pr_headers')->where('id', $header->id)->update([
                    'pr_status'       => $nextStatus,
                    'current_role_id' => $isFinalStep ? null : $nextStep->role_id,
                    'current_step_id' => $isFinalStep ? null : $nextStep->id,
                    'approver_id'     => $user->id,
                    'approved_at'     => $now,
                    'updated_at'      => $now,
                ]);

                $header->refresh()->loadMissing('detail');
                $detail      = $header->detail;
                $latestItems = $header->items()->withTrashed()->orderBy('id')->get();

                $requestedItemsPayload = [
                    'items' => $latestItems->values()->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'item_category_id' => $item->item_category_id,
                            'type' => $item->type,
                            'size' => $item->size,
                            'quantity' => (float) $item->quantity,
                            'quantity_approve' => $item->quantity_approve !== null ? (float) $item->quantity_approve : null,
                            'unit' => $item->unit,
                            'remaining' => (float) $item->remaining,
                            'item_priority' => $item->item_priority,
                            'status' => $item->status,
                            'deleted_at' => $item->deleted_at,
                            'keterangan' => $item->description,
                        ];
                    })->toArray(),
                    'next_step_id'      => $header->current_step_id,
                    'next_role_id'      => $header->current_role_id,
                ];

                PrLog::create([
                    'batch_id'             => $batchId,
                    'action'               => 'APPROVE',
                    'status'               => 'SUCCESS',
                    'notes'                => 'Approver memproses pengajuan dan memperbarui item.',
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
                    'latitude'             => $detail?->latitude,
                    'longitude'            => $detail?->longitude,
                    'delivery_address'     => $detail?->delivery_address,
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
                    'latitude'             => $detail?->latitude,
                    'longitude'            => $detail?->longitude,
                    'delivery_address'     => $detail?->delivery_address,
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
                        'item_priority'    => $item->item_priority,
                        'status'           => $item->status,
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
            ->body('Perubahan item dan status PR berhasil disimpan.')
            ->send();

        $this->redirect(ApprovedPrList::getUrl(), navigate: true);
    }

    public function resetFilters(): void
    {
        $this->search       = '';
        $this->statusFilter = '';
        $this->needsFilter  = '';
        $this->visibleCount = $this->initialTake;
        $this->resetPage();
    }
}
