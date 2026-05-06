<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Models\Item;
use App\Models\PrLog;
use Filament\Pages\Page;
use UnitEnum;

class PurchaseRequisition extends Page
{
    public string $search = '';
    public ?string $submittedDate = null;
    public string $sortColumn = 'submitted_at';
    public string $sortDirection = 'desc';

    public ?int $expandedItemId = null;
    public bool $showDetailModal = false;
    public ?int $selectedDetailItemId = null;
    public bool $showItemHistoryModal = false;
    public array $selectedItemHistory = [];

    protected static ?string $navigationLabel = 'Daftar Pengajuan Barang';
    protected static ?string $title = 'Daftar Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.app.pages.purchase-requisition';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', \App\Constants\RoleConstant::VESSEL_CREW_REQUESTER)->exists() ?? false;
    }

    public function updatedSearch()
    {
        // No pagination state to reset.
    }

    public function updatedSubmittedDate()
    {
        // No pagination state to reset.
    }

    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = $column === 'submitted_at' ? 'desc' : 'asc';
        }

        // No pagination state to reset.
    }

    public function resetDateFilters(): void
    {
        $this->search = '';
        $this->submittedDate = null;
        $this->sortColumn = 'submitted_at';
        $this->sortDirection = 'desc';
        $this->dispatch('pr-date-filters-reset');
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        $query = Item::query()
            ->select('items.*')
            ->join('pr_details', 'pr_details.id', '=', 'items.pr_detail_id')
            ->join('pr_headers', 'pr_headers.id', '=', 'pr_details.pr_header_id')
            ->leftJoin('item_categories', 'item_categories.id', '=', 'items.item_category_id')
            ->leftJoin('vessels', 'vessels.id', '=', 'pr_details.vessel_id')
            ->with(['itemCategory', 'detail.vessel', 'detail.header.currentRole'])
            ->where('pr_headers.requester_id', $user?->id)
            ->whereNotIn('pr_headers.pr_status', [
                PrStatusConstant::REJECTED,
                PrStatusConstant::CLOSED,
            ])
            ->whereNotNull('pr_headers.current_step_id')
            ->whereNotNull('pr_headers.current_role_id');

        if ($this->search) {
            $keyword = '%' . $this->search . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('items.type', 'like', $keyword)
                    ->orWhere('pr_details.document_no', 'like', $keyword)
                    ->orWhere('item_categories.name', 'like', $keyword);
            });
        }

        if ($this->submittedDate) {
            $query->whereDate('pr_headers.created_at', $this->submittedDate);
        }

        $sortableColumns = [
            'document_no' => 'pr_details.document_no',
            'category' => 'item_categories.name',
            'type' => 'items.type',
            'vessel' => 'vessels.name',
            'status' => 'pr_headers.pr_status',
            'po_status' => 'items.po_number',
            'submitted_at' => 'pr_headers.created_at',
        ];

        $sortColumn = $sortableColumns[$this->sortColumn] ?? 'pr_headers.created_at';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        if ($this->sortColumn === 'po_status') {
            $query->orderByRaw("CASE WHEN items.po_number IS NULL OR items.po_number = '' THEN 1 ELSE 0 END {$sortDirection}");
            $query->orderBy('pr_headers.created_at', 'desc');
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        $totalItems = (clone $query)->count();
        $poProgress = (clone $query)->whereNotNull('items.po_number')->where('items.po_number', '!=', '')->count();

        $itemList = $query->get();

        $selectedDetailItem = null;
        if ($this->selectedDetailItemId) {
            $selectedDetailItem = Item::with(['itemCategory', 'detail.vessel', 'detail.header.currentRole'])
                ->find($this->selectedDetailItemId);
        }

        $summary = [
            'total_items' => $totalItems,
            'po_progress' => $poProgress,
        ];

        return [
            'itemList' => $itemList,
            'summary' => $summary,
            'selectedDetailItem' => $selectedDetailItem,
        ];
    }

    public function openDetailModal(int $itemId): void
    {
        $this->selectedDetailItemId = $itemId;
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedDetailItemId = null;
    }

    public function toggleExpand(int $itemId): void
    {
        $this->expandedItemId = $this->expandedItemId === $itemId ? null : $itemId;
    }

    public function openItemHistory(int $itemId): void
    {
        $item = Item::with(['itemCategory', 'detail.header'])->find($itemId);
        if (! $item || ! $item->detail?->header) {
            return;
        }

        $header = $item->detail->header;
        $logs = PrLog::where('batch_id', $header->batch_id)
            ->whereNotNull('payload')
            ->orderBy('id')
            ->get();

        $initialQty = null;
        $approvedQty = null;

        foreach ($logs as $log) {
            $payloadItems = $log->payload['items'] ?? null;
            if (! is_array($payloadItems)) {
                continue;
            }

            foreach ($payloadItems as $payloadItem) {
                $isSameItem = isset($payloadItem['id']) && (int) $payloadItem['id'] === (int) $item->id;

                if (! $isSameItem) {
                    $isSameItem = ($payloadItem['type'] ?? null) === $item->type
                        && ($payloadItem['size'] ?? null) === $item->size;
                }

                if (! $isSameItem) {
                    continue;
                }

                $qty = isset($payloadItem['quantity']) ? (float) $payloadItem['quantity'] : null;
                if ($qty === null) {
                    continue;
                }

                if ($initialQty === null) {
                    $initialQty = $qty;
                }

                $approvedQty = $qty;
            }
        }

        if ($initialQty === null) {
            $initialQty = (float) $item->quantity;
        }

        if ($approvedQty === null) {
            $approvedQty = (float) $item->quantity;
        }

        $this->selectedItemHistory = [
            'pr_number' => $header->pr_number,
            'document_no' => $item->detail?->document_no,
            'type' => $item->type,
            'size' => $item->size,
            'category' => $item->itemCategory?->name ?? '—',
            'unit' => $item->unit,
            'initial_qty' => $initialQty,
            'approved_qty' => $approvedQty,
            'difference' => $approvedQty - $initialQty,
        ];

        $this->showItemHistoryModal = true;
    }

    public function closeItemHistoryModal(): void
    {
        $this->showItemHistoryModal = false;
        $this->selectedItemHistory = [];
    }
}
