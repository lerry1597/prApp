<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PrHeader;
use App\Models\PrLog;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PurchaseRequisitionRequestList extends Page
{
    public string $search = '';
    public ?string $requestDate = null;
    public int $initialTake = 10;
    public int $loadStep = 10;
    public int $visibleCount = 10;
    public bool $hasMoreRows = false;

    public bool $showFlowModal = false;
    public ?array $selectedFlowHeader = null;
    public array $latestItems = [];
    public bool $showItemChangesModal = false;
    public array $itemChangeSteps = [];
    public array $itemSnapshots = [];

    protected static ?string $navigationLabel = 'Daftar Permintaan Barang';
    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Daftar Permintaan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';
    protected string $view = 'filament.app.pages.purchase-requisition-request-list';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists() ?? false;
    }

    public function updatedSearch(): void
    {
        $this->visibleCount = $this->initialTake;
    }

    public function updatedRequestDate(): void
    {
        $this->visibleCount = $this->initialTake;
    }

    public function resetDateFilters(): void
    {
        $this->search = '';
        $this->requestDate = null;
        $this->visibleCount = $this->initialTake;
        $this->dispatch('pr-request-list-date-filters-reset');
    }

    public function loadMore(): void
    {
        if (!$this->hasMoreRows) {
            return;
        }

        $this->visibleCount += $this->loadStep;
    }

    public function getViewData(): array
    {
        $userId = auth()->id();
        $matchedItemHints = [];

        $query = PrHeader::query()
            ->with(['detail.vessel', 'items.itemCategory'])
            ->visibleToUser(auth()->user());

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';

            $query->where(function ($q) use ($search): void {
                $q->where('pr_number', 'like', $search)
                    ->orWhereHas('detail', function ($detailQuery) use ($search): void {
                        $detailQuery->where('document_no', 'like', $search)
                            ->orWhereHas('vessel', function ($vesselQuery) use ($search): void {
                                $vesselQuery->where('name', 'like', $search);
                            });
                    })
                    ->orWhereHas('department', function ($deptQuery) use ($search): void {
                        $deptQuery->where('name', 'like', $search);
                    })
                    ->orWhereHas('items', function ($itemQuery) use ($search): void {
                        $itemQuery->where('type', 'like', $search);
                    });
            });
        }

        if ($this->requestDate) {
            $query->whereDate('created_at', '=', Carbon::parse($this->requestDate)->toDateString());
        }

        $rows = $query
            ->latest('id')
            ->limit($this->visibleCount + 1)
            ->get();

        $this->hasMoreRows = $rows->count() > $this->visibleCount;
        $requestList = $rows->take($this->visibleCount)->values();

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';
            $headerIds = $requestList->pluck('id')->filter()->unique()->values();

            if ($headerIds->isNotEmpty()) {
                $matchedItemHints = Item::query()
                    ->select('pr_details.pr_header_id', 'items.type')
                    ->join('pr_details', 'pr_details.id', '=', 'items.pr_detail_id')
                    ->whereIn('pr_details.pr_header_id', $headerIds)
                    ->where('items.type', 'like', $search)
                    ->get()
                    ->groupBy('pr_header_id')
                    ->map(function (Collection $rows): array {
                        return $rows->pluck('type')
                            ->unique()
                            ->take(3)
                            ->values()
                            ->all();
                    })
                    ->toArray();
            }
        }

        return [
            'requestList' => $requestList,
            'matchedItemHints' => $matchedItemHints,
            'statusMap' => PrStatusConstant::getStatuses(),
        ];
    }

    public function showFlowDetails(int $headerId): void
    {
        $header = PrHeader::with(['detail.vessel', 'items.itemCategory', 'requester'])->find($headerId);
        if (!$header) return;

        $this->selectedFlowHeader = [
            'id' => $header->id,
            'title' => $header->detail?->title,
            'document_no' => $header->detail?->document_no,
            'pr_number' => $header->pr_number,
            'vessel_name' => $header->detail?->vessel?->name,
            'needs' => $header->detail?->needs,
            'request_date' => $header->created_at?->format('d M Y H:i'),
            'client_request_date' => $header->detail?->request_date_client?->format('d M Y H:i'),
            'status_label' => PrStatusConstant::getStatuses()[$header->pr_status] ?? $header->pr_status,
            'status_code' => $header->pr_status,
            'requester_name' => $header->requester?->name,
            'delivery_address' => $header->detail?->delivery_address,
        ];

        $this->latestItems = $header->items->map(function ($item) {
            return [
                'category' => $item->itemCategory?->name,
                'item' => $item->type,
                'size' => $item->size,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'priority' => $item->priority,
                'remaining' => $item->remaining,
                'po_number' => $item->po_number,
            ];
        })->toArray();

        $this->showFlowModal = true;
    }

    public function closeFlowDetails(): void
    {
        $this->showFlowModal = false;
        $this->selectedFlowHeader = null;
        $this->latestItems = [];
    }

    public function showItemChanges(): void
    {
        if (!$this->selectedFlowHeader) {
            return;
        }

        $logs = PrLog::query()
            ->where('pr_header_id', $this->selectedFlowHeader['id'])
            ->orderBy('id', 'asc')
            ->get();

        $payload = $this->buildPayloadItemChanges($logs);

        $this->itemChangeSteps = $payload['changes'];
        $this->itemSnapshots = $payload['snapshots'];
        $this->showItemChangesModal = true;
    }

    public function closeItemChanges(): void
    {
        $this->showItemChangesModal = false;
        $this->itemChangeSteps = [];
        $this->itemSnapshots = [];
    }

    private function buildPayloadItemChanges(Collection $steps): array
    {
        $categoryMap = $this->resolveItemCategoryMap($steps);

        $previous = [];
        $latestItems = [];
        $changesByStep = [];
        $snapshots = [];

        foreach ($steps->values() as $index => $step) {
            $payload = is_array($step->payload ?? null) ? $step->payload : [];
            $items = $this->normalizePayloadItems($payload['items'] ?? [], $categoryMap);

            $current = [];
            foreach ($items as $item) {
                $current[$item['key']] = $item;
            }

            $snapshots[] = [
                'step_no' => $index + 1,
                'status_label' => PrStatusConstant::getStatuses()[$step->pr_status] ?? ($step->pr_status ?: '-'),
                'changed_at' => $this->formatDateTime($step->created_at),
                'rows' => array_values($items),
            ];

            $rowsForStep = [];
            $usedPreviousKeys = [];
            foreach ($current as $key => $after) {
                $beforeKey = null;
                if (isset($previous[$key])) {
                    $beforeKey = $key;
                } else {
                    $beforeKey = $this->findBestPreviousKey($previous, $after, $usedPreviousKeys);
                }

                if ($beforeKey !== null) {
                    $usedPreviousKeys[$beforeKey] = true;
                }

                $before = $beforeKey !== null ? ($previous[$beforeKey] ?? null) : null;
                $changedFields = $index === 0 ? [] : $this->diffChangedFields($before, $after);

                $changeLabel = 'Tetap';
                if ($index === 0) {
                    $changeLabel = 'Awal';
                } elseif ($before === null) {
                    $changeLabel = 'Ditambahkan';
                } elseif ($changedFields !== []) {
                    $changeLabel = 'Diubah';
                }

                $rowsForStep[] = [
                    'change' => $changeLabel,
                    'before' => $before,
                    'after' => $after,
                    'changed_fields' => $changedFields,
                    'is_deleted_current' => (bool) ($after['is_deleted'] ?? false),
                ];
            }

            $changesByStep[] = [
                'step_no' => $index + 1,
                'status_label' => PrStatusConstant::getStatuses()[$step->pr_status] ?? ($step->pr_status ?: '-'),
                'changed_at' => $this->formatDateTime($step->created_at),
                'rows' => array_values($rowsForStep),
            ];

            $previous = $current;
            $latestItems = array_values(array_filter($current, fn(array $row): bool => empty($row['is_deleted'])));
        }

        return [
            'latest_items' => $latestItems,
            'changes' => $changesByStep,
            'snapshots' => $snapshots,
        ];
    }

    private function findBestPreviousKey(array $previousRows, array $after, array $usedPreviousKeys): ?string
    {
        $category = $after['category'] ?? '-';
        $item = $after['item'] ?? '-';
        $size = $after['size'] ?? '-';
        $unit = $after['unit'] ?? '-';

        $tier1 = [];
        $tier2 = [];

        foreach ($previousRows as $prevKey => $prevRow) {
            if (($usedPreviousKeys[$prevKey] ?? false) === true) {
                continue;
            }

            $prevCategory = $prevRow['category'] ?? '-';
            $prevItem = $prevRow['item'] ?? '-';
            $prevSize = $prevRow['size'] ?? '-';
            $prevUnit = $prevRow['unit'] ?? '-';

            if ($prevCategory === $category && $prevItem === $item && $prevUnit === $unit) {
                $tier1[] = $prevKey;
                continue;
            }

            if ($prevCategory === $category && $prevItem === $item) {
                $tier2[] = $prevKey;
            }
        }

        if (count($tier1) === 1) {
            return $tier1[0];
        }

        foreach ($tier1 as $candidate) {
            if (($previousRows[$candidate]['size'] ?? '-') === $size) {
                return $candidate;
            }
        }

        if ($tier1 !== []) {
            return $tier1[0];
        }

        if (count($tier2) === 1) {
            return $tier2[0];
        }

        return $tier2[0] ?? null;
    }

    private function resolveItemCategoryMap(Collection $steps): array
    {
        $categoryIds = [];

        foreach ($steps as $step) {
            $payload = is_array($step->payload ?? null) ? $step->payload : [];
            $items = $payload['items'] ?? [];

            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $categoryId = $item['item_category_id'] ?? null;
                if ($categoryId !== null && $categoryId !== '') {
                    $categoryIds[] = (int) $categoryId;
                }
            }
        }

        if ($categoryIds === []) {
            return [];
        }

        return ItemCategory::query()
            ->whereIn('id', array_values(array_unique($categoryIds)))
            ->pluck('name', 'id')
            ->map(fn($name) => $this->stringValue($name))
            ->all();
    }

    private function normalizePayloadItems(mixed $items, array $categoryMap): array
    {
        if (!is_array($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $categoryId = $item['item_category_id'] ?? null;

            $category = $this->stringValue(
                $item['category']
                    ?? $item['item_category_name']
                    ?? $item['item_category']
                    ?? (($categoryId !== null && $categoryId !== '') ? ($categoryMap[(int) $categoryId] ?? null) : null)
                    ?? null
            );

            $itemName = $this->stringValue(
                $item['item']
                    ?? $item['item_type']
                    ?? $item['type']
                    ?? null
            );

            $size = $this->stringValue($item['size'] ?? null);
            $unit = $this->stringValue($item['unit'] ?? null);
            $quantity = $item['quantity'] ?? 0;
            $remaining = $item['remaining'] ?? 0;
            $priority = $this->stringValue($item['priority'] ?? null);
            $isDeleted = (bool) ($item['is_deleted'] ?? false);

            $key = ($item['id'] ?? null) ?: md5($category . $itemName . $size . $unit);

            $result[] = [
                'key' => (string) $key,
                'category' => $category,
                'item' => $itemName,
                'size' => $size,
                'quantity' => $quantity,
                'unit' => $unit,
                'remaining' => $remaining,
                'priority' => $priority,
                'is_deleted' => $isDeleted,
            ];
        }

        return $result;
    }

    private function diffChangedFields(?array $before, array $after): array
    {
        if ($before === null) {
            return [];
        }

        $fields = ['category', 'item', 'size', 'quantity', 'unit', 'priority'];
        $changed = [];

        foreach ($fields as $field) {
            $valBefore = $before[$field] ?? null;
            $valAfter = $after[$field] ?? null;

            if ((string) $valBefore !== (string) $valAfter) {
                $changed[] = $field;
            }
        }

        return $changed;
    }

    private function stringValue(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return (string) $value;
    }

    private function formatDateTime(?Carbon $dateTime): string
    {
        if (!$dateTime) {
            return '-';
        }

        return $dateTime->format('d M Y H:i:s');
    }
}
