<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\ItemLog;
use App\Models\PrHistory;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\WithPagination;

class PurchaseRequisitionHistory extends Page
{
    use WithPagination;

    public string $search = '';
    public ?string $startDate = null;
    public ?string $endDate = null;

    public bool $showFlowModal = false;

    public ?array $selectedFlowHeader = null;

    /** @var array<int, array<string, mixed>> */
    public array $flowSteps = [];

    /** @var array<int, array<int, array<string, string>>> */
    public array $flowFieldChanges = [];

    /** @var array<int, array<string, mixed>> */
    public array $flowItemChanges = [];

    protected static ?string $navigationLabel = 'Riwayat Pengajuan PR';
    protected static ?string $title = 'Riwayat Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected string $view = 'filament.app.pages.purchase-requisition-history';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists() ?? false;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->resetPage();
    }

    public function resetDateFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->resetPage();
        $this->dispatch('pr-history-date-filters-reset');
    }

    public function getViewData(): array
    {
        $user = auth()->user();
        $userId = $user?->id;

        $latestIdsPerBatch = PrHistory::query()
            ->selectRaw('MAX(id)')
            ->where('requester_id', $userId)
            ->groupBy('batch_id');

        $query = PrHistory::query()
            ->with(['approver'])
            ->whereIn('id', $latestIdsPerBatch)
            ->where('requester_id', $userId)
            ->whereNull('current_step_id');

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';

            $query->where(function ($q) use ($search): void {
                $q->where('pr_number', 'like', $search)
                    ->orWhere('title', 'like', $search)
                    ->orWhere('needs', 'like', $search)
                    ->orWhere('document_no', 'like', $search);
            });
        }

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $historyList = $query
            ->latest('id')
            ->paginate(8);

        return [
            'historyList' => $historyList,
            'statusLabels' => PrStatusConstant::getStatuses(),
        ];
    }

    public function showFlowDetails(string $batchId): void
    {
        $userId = auth()->id();

        $steps = PrHistory::query()
            ->with(['approver', 'currentRole', 'currentStep'])
            ->where('batch_id', $batchId)
            ->where('requester_id', $userId)
            ->orderBy('id')
            ->get();

        if ($steps->isEmpty()) {
            return;
        }

        $latest = $steps->last();

        $this->selectedFlowHeader = [
            'batch_id' => $batchId,
            'pr_number' => $latest->pr_number,
            'title' => $latest->title,
            'needs' => $latest->needs,
            'status' => $latest->pr_status,
            'status_label' => PrStatusConstant::getStatuses()[$latest->pr_status] ?? ($latest->pr_status ?: '-'),
            'document_no' => $latest->document_no,
            'request_date' => optional($latest->request_date)->format('d M Y'),
            'updated_at' => optional($latest->updated_at)->format('d M Y H:i'),
        ];

        $this->flowSteps = $steps->values()->map(function (PrHistory $row, int $index): array {
            return [
                'index' => $index,
                'status' => $row->pr_status,
                'status_label' => PrStatusConstant::getStatuses()[$row->pr_status] ?? ($row->pr_status ?: '-'),
                'approver' => $row->approver?->name ?? 'System / Belum ada',
                'role' => $row->currentRole?->name ?? '-',
                'step' => $row->currentStep?->step_order,
                'created_at' => optional($row->created_at)->format('d M Y H:i:s'),
            ];
        })->all();

        $this->flowFieldChanges = $this->buildFlowFieldChanges($steps);
        $this->flowItemChanges = $this->buildFlowItemChanges($batchId);

        $this->showFlowModal = true;
    }

    public function closeFlowDetails(): void
    {
        $this->showFlowModal = false;
        $this->selectedFlowHeader = null;
        $this->flowSteps = [];
        $this->flowFieldChanges = [];
        $this->flowItemChanges = [];
    }

    /**
     * @return array<int, array<int, array<string, string>>>
     */
    private function buildFlowFieldChanges(Collection $steps): array
    {
        $fields = [
            'pr_status' => 'Status',
            'approver_id' => 'Approver',
            'current_role_id' => 'Role Saat Ini',
            'current_step_id' => 'Step Saat Ini',
            'title' => 'Judul',
            'needs' => 'Kebutuhan',
            'document_no' => 'No. Dokumen',
            'required_date' => 'Tanggal Dibutuhkan',
            'expired_date' => 'Tanggal Kedaluwarsa',
        ];

        $changes = [];

        foreach ($steps->values() as $index => $current) {
            $previous = $index > 0 ? $steps[$index - 1] : null;

            if ($previous === null) {
                $changes[$index] = [[
                    'field' => 'Tahap Awal',
                    'from' => '-',
                    'to' => 'Pengajuan awal dicatat ke riwayat',
                ]];
                continue;
            }

            $rowChanges = [];

            foreach ($fields as $field => $label) {
                $before = (string) ($previous->{$field} ?? '-');
                $after = (string) ($current->{$field} ?? '-');

                if ($before !== $after) {
                    $rowChanges[] = [
                        'field' => $label,
                        'from' => $before,
                        'to' => $after,
                    ];
                }
            }

            $changes[$index] = $rowChanges;
        }

        return $changes;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildFlowItemChanges(string $batchId): array
    {
        $rows = ItemLog::query()
            ->with('itemCategory')
            ->where('batch_id', $batchId)
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        // Snapshot item dicatat berkelompok pada timestamp yang sama/sangat dekat.
        $snapshots = $rows->groupBy(fn($r) => $r->created_at?->format('Y-m-d H:i:s') ?? 'unknown')
            ->values();

        $result = [];
        $previous = [];

        foreach ($snapshots as $index => $snapshotRows) {
            $current = [];

            foreach ($snapshotRows as $row) {
                $key = ($row->no ?? '') . '|' . ($row->type ?? '') . '|' . ($row->size ?? '');

                $current[$key] = [
                    'item' => trim(($row->type ?? '-') . ' ' . ($row->size ?? '')),
                    'category' => $row->itemCategory?->name ?? '-',
                    'quantity' => (string) ($row->quantity ?? '-'),
                    'unit' => (string) ($row->unit ?? '-'),
                    'remaining' => (string) ($row->remaining ?? '-'),
                ];
            }

            $added = [];
            $updated = [];
            $removed = [];

            foreach ($current as $key => $item) {
                if (! isset($previous[$key])) {
                    $added[] = $item;
                    continue;
                }

                $before = $previous[$key];
                $changes = [];

                foreach (['quantity', 'unit', 'remaining'] as $prop) {
                    if (($before[$prop] ?? '') !== ($item[$prop] ?? '')) {
                        $changes[] = [
                            'field' => $prop,
                            'from' => $before[$prop] ?? '-',
                            'to' => $item[$prop] ?? '-',
                        ];
                    }
                }

                if ($changes !== []) {
                    $updated[] = [
                        'item' => $item['item'],
                        'changes' => $changes,
                    ];
                }
            }

            foreach ($previous as $key => $item) {
                if (! isset($current[$key])) {
                    $removed[] = $item;
                }
            }

            $result[$index] = [
                'added' => $added,
                'updated' => $updated,
                'removed' => $removed,
            ];

            $previous = $current;
        }

        return $result;
    }
}
