<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PrHistory;
use App\Models\Vessel;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PurchaseRequisitionHistory extends Page
{
    public string $search = '';
    public ?string $requestDate = null;
    public int $initialTake = 10;
    public int $loadStep = 10;
    public int $visibleCount = 10;
    public bool $hasMoreRows = false;

    public bool $showFlowModal = false;
    public bool $showItemChangesModal = false;

    public ?array $selectedFlowHeader = null;

    /** @var array<int, array<string, string>> */
    public array $latestItems = [];

    /** @var array<int, array<string, mixed>> */
    public array $itemChangeSteps = [];

    /** @var array<int, array<string, mixed>> */
    public array $itemSnapshots = [];

    protected static ?string $navigationLabel = 'Riwayat Pengajuan Barang';
    protected static ?int $navigationSort = 4;

    protected static ?string $title = 'Riwayat Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected string $view = 'filament.app.pages.purchase-requisition-history';

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
        $this->dispatch('pr-history-date-filters-reset');
    }

    public function loadMore(): void
    {
        if (! $this->hasMoreRows) {
            return;
        }

        $this->visibleCount += $this->loadStep;
    }

    public function getViewData(): array
    {
        $userId = auth()->id();
        $matchedItemHints = [];

        $latestIdsPerBatch = PrHistory::query()
            ->selectRaw('MAX(id)')
            ->where('requester_id', $userId)
            ->groupBy('batch_id');

        $query = PrHistory::query()
            ->whereIn('id', $latestIdsPerBatch)
            ->where('requester_id', $userId)
            ->whereNull('current_step_id');

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';

            $query->where(function ($q) use ($search): void {
                $q->where('document_no', 'like', $search)
                    ->orWhere('needs', 'like', $search)
                    ->orWhere('title', 'like', $search)
                    ->orWhere('pr_number', 'like', $search)
                    ->orWhereExists(function ($itemQuery) use ($search): void {
                        $itemQuery->selectRaw('1')
                            ->from('pr_details')
                            ->join('items', 'items.pr_detail_id', '=', 'pr_details.id')
                            ->whereColumn('pr_details.pr_header_id', 'pr_histories.pr_header_id')
                            ->where('items.type', 'like', $search);
                    });
            });
        }

        if ($this->requestDate) {
            $query->whereDate('request_date', '=', Carbon::parse($this->requestDate)->toDateString());
        }

        $rows = $query
            ->latest('id')
            ->limit($this->visibleCount + 1)
            ->get();

        $this->hasMoreRows = $rows->count() > $this->visibleCount;
        $historyList = $rows->take($this->visibleCount)->values();

        if ($this->search !== '') {
            $search = '%' . $this->search . '%';

            $headerIds = $historyList
                ->pluck('pr_header_id')
                ->filter()
                ->unique()
                ->values();

            if ($headerIds->isNotEmpty()) {
                $matchedItemHints = Item::query()
                    ->withTrashed()
                    ->select('pr_details.pr_header_id', 'items.type')
                    ->join('pr_details', 'pr_details.id', '=', 'items.pr_detail_id')
                    ->whereIn('pr_details.pr_header_id', $headerIds)
                    ->where('items.type', 'like', $search)
                    ->orderBy('items.type')
                    ->get()
                    ->groupBy('pr_header_id')
                    ->map(function (Collection $rows): array {
                        return $rows->pluck('type')
                            ->map(fn($type) => $this->stringValue($type))
                            ->filter(fn($type) => $type !== '-')
                            ->unique()
                            ->take(3)
                            ->values()
                            ->all();
                    })
                    ->all();
            }
        }

        return [
            'historyList' => $historyList,
            'statusLabels' => PrStatusConstant::getStatuses(),
            'matchedItemHints' => $matchedItemHints,
            'hasMoreRows' => $this->hasMoreRows,
        ];
    }

    public function showFlowDetails(string $batchId): void
    {
        $userId = auth()->id();

        $steps = PrHistory::query()
            ->where('batch_id', $batchId)
            ->where('requester_id', $userId)
            ->orderBy('id')
            ->get();

        if ($steps->isEmpty()) {
            return;
        }

        $latest = $steps->last();

        $vesselNameMap = Vessel::query()
            ->whereIn('id', $steps->pluck('vessel_id')->filter()->unique())
            ->pluck('name', 'id');

        $departmentNameMap = Department::query()
            ->whereIn('id', $steps->pluck('department_id')->filter()->unique())
            ->pluck('name', 'id');

        $latestPayload = is_array($latest->payload ?? null) ? $latest->payload : [];

        $vesselName = $this->stringValue($latestPayload['vessel_name'] ?? null);
        if ($vesselName === '-' && $latest->vessel_id) {
            $vesselName = $this->stringValue($vesselNameMap[$latest->vessel_id] ?? null);
        }

        $departmentName = $this->stringValue($latestPayload['department_name'] ?? null);
        if ($departmentName === '-' && $latest->department_id) {
            $departmentName = $this->stringValue($departmentNameMap[$latest->department_id] ?? null);
        }

        $itemData = $this->buildPayloadItemChanges($steps);

        $this->selectedFlowHeader = [
            'batch_id' => $batchId,
            'document_no' => $this->stringValue($latest->document_no),
            'pr_number' => $this->stringValue($latest->pr_number),
            'title' => $this->stringValue($latest->title),
            'needs' => $this->stringValue($latest->needs),
            'status' => $latest->pr_status,
            'status_label' => PrStatusConstant::getStatuses()[$latest->pr_status] ?? ($latest->pr_status ?: '-'),
            'request_date' => $this->formatDate($latest->request_date),
            'client_request_date' => $this->formatDate($latestPayload['request_date'] ?? $latest->request_date),
            'vessel_name' => $vesselName,
            'department_name' => $departmentName,
        ];

        $this->latestItems = $itemData['latest_items'];
        $this->itemChangeSteps = $itemData['changes'];
        $this->itemSnapshots = $itemData['snapshots'];

        $this->showItemChangesModal = false;
        $this->showFlowModal = true;
    }

    public function openItemChangesModal(): void
    {
        $this->showItemChangesModal = true;
    }

    public function closeItemChangesModal(): void
    {
        $this->showItemChangesModal = false;
    }

    public function closeFlowDetails(): void
    {
        $this->showFlowModal = false;
        $this->showItemChangesModal = false;
        $this->selectedFlowHeader = null;
        $this->latestItems = [];
        $this->itemChangeSteps = [];
        $this->itemSnapshots = [];
    }

    /**
     * @return array{latest_items: array<int, array<string, string>>, changes: array<int, array<string, mixed>>, snapshots: array<int, array<string, mixed>>}
     */
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

    /**
     * @param array<string, array<string, mixed>> $previousRows
     * @param array<string, mixed> $after
     * @param array<string, bool> $usedPreviousKeys
     */
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

            // Strong match: category + item + unit (quantity edits will still match here)
            if ($prevCategory === $category && $prevItem === $item && $prevUnit === $unit) {
                $tier1[] = $prevKey;
                continue;
            }

            // Fallback match: category + item
            if ($prevCategory === $category && $prevItem === $item) {
                $tier2[] = $prevKey;
            }
        }

        if (count($tier1) === 1) {
            return $tier1[0];
        }

        // If multiple strong candidates, prefer same size.
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

    /**
     * @return array<int, string>
     */
    private function resolveItemCategoryMap(Collection $steps): array
    {
        $categoryIds = [];

        foreach ($steps as $step) {
            $payload = is_array($step->payload ?? null) ? $step->payload : [];
            $items = $payload['items'] ?? [];

            if (! is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                if (! is_array($item)) {
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

    /**
     * @param mixed $items
     * @param array<int, string> $categoryMap
     * @return array<int, array<string, mixed>>
     */
    private function normalizePayloadItems(mixed $items, array $categoryMap): array
    {
        if (! is_array($items)) {
            return [];
        }

        $result = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
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
                    ?? $item['type']
                    ?? $item['name']
                    ?? null
            );

            $size = $this->stringValue(
                $item['size']
                    ?? $item['specification']
                    ?? $item['spesifikasi']
                    ?? '-'
            );

            $rowNo = $this->stringValue($item['no'] ?? $item['line_no'] ?? null);
            $quantity = $this->stringValue($item['quantity'] ?? $item['qty'] ?? null);
            $unit = $this->stringValue($item['unit'] ?? null);
            $remaining = $this->stringValue($item['remaining'] ?? $item['sisa'] ?? null);
            $itemPriority = $this->stringValue($item['item_priority'] ?? $item['priority'] ?? $item['urgency'] ?? null);
            $description = $this->stringValue($item['description'] ?? $item['keterangan'] ?? $item['Keterangan'] ?? $item['notes'] ?? null);

            $statusCodeRaw = $this->stringValue($item['status'] ?? null);
            $statusCode = $statusCodeRaw === '-' ? PrStatusConstant::UNKNOWN : $statusCodeRaw;
            $statusLabel = PrStatusConstant::getStatuses()[$statusCode] ?? $statusCode;
            $statusColor = PrStatusConstant::getColor($statusCode);

            $notes = $description;

            $rawDeletedAt = $item['deleted_at'] ?? null;
            $isDeleted = $this->hasDeletedAtValue($rawDeletedAt);
            $deletedAt = $isDeleted ? $this->stringValue($rawDeletedAt) : '-';

            $identity = $this->stringValue($item['id'] ?? null);
            if ($identity !== '-') {
                $key = 'id:' . $identity;
            } elseif ($rowNo !== '-') {
                $key = 'no:' . $rowNo;
            } else {
                $key = 'sig:' . md5($category . '|' . $itemName . '|' . $size . '|' . $unit);
            }

            $result[] = [
                'key' => $key,
                'category' => $category,
                'item' => $itemName,
                'size' => $size,
                'quantity' => $quantity,
                'unit' => $unit,
                'remaining' => $remaining,
                'item_priority' => $itemPriority,
                'description' => $description,
                'status' => $statusCode,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'notes' => $notes,
                'deleted_at' => $deletedAt,
                'is_deleted' => $isDeleted,
            ];
        }

        return $result;
    }

    /**
     * @param array<string, string> $before
     * @param array<string, string> $after
     */
    private function rowChanged(array $before, array $after): bool
    {
        return $this->diffChangedFields($before, $after) !== [];
    }

    /**
     * @param array<string, mixed>|null $before
     * @param array<string, mixed>|null $after
     * @return array<int, string>
     */
    private function diffChangedFields(?array $before, ?array $after): array
    {
        $fields = ['category', 'item', 'size', 'quantity', 'unit', 'remaining', 'deleted_at'];
        $changed = [];

        foreach ($fields as $field) {
            $beforeValue = $before[$field] ?? '-';
            $afterValue = $after[$field] ?? '-';

            if ($before === null && ($afterValue ?? '-') !== '-') {
                $changed[] = $field;
                continue;
            }

            if ($beforeValue !== $afterValue) {
                $changed[] = $field;
            }
        }

        return $changed;
    }

    private function stringValue(mixed $value): string
    {
        if ($value === null) {
            return '-';
        }

        $text = trim((string) $value);
        return $text === '' ? '-' : $text;
    }

    private function hasDeletedAtValue(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return false;
        }

        $normalized = strtolower($text);
        return ! in_array($normalized, ['-', 'null', '0000-00-00', '0000-00-00 00:00:00'], true);
    }

    private function formatDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if ($value instanceof Carbon) {
            return $value->format('d M Y');
        }

        try {
            return Carbon::parse((string) $value)->format('d M Y');
        } catch (\Throwable) {
            return $this->stringValue($value);
        }
    }

    private function formatDateTime(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        if ($value instanceof Carbon) {
            return $value->format('d M Y H:i:s');
        }

        try {
            return Carbon::parse((string) $value)->format('d M Y H:i:s');
        } catch (\Throwable) {
            return $this->stringValue($value);
        }
    }
}
