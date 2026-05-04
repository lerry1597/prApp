<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\PrHeader;
use App\Models\PrLog;
use App\Models\ItemLog;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\WithPagination;

class PrFlowHistory extends Page
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public bool $showTimelinePanel = false;
    public ?int $selectedPrId = null;

    protected static ?string $navigationLabel = 'Riwayat Lengkap';
    protected static ?string $title = 'Riwayat Lengkap Alur PR';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    protected static string|\UnitEnum|null $navigationGroup = 'Procurement Officer';
    protected static ?int $navigationSort = 4;
    protected string $view = 'filament.app.pages.pr-flow-history';

    /** Terminal statuses = PRs that have completed their lifecycle */
    private const TERMINAL_STATUSES = [
        PrStatusConstant::CONVERTED_TO_PO,
        PrStatusConstant::REJECTED,
        PrStatusConstant::CLOSED,
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', RoleConstant::PROCUREMENT_OFFICER)->exists() ?? false;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->resetPage();
    }

    public function openTimeline(int $id): void
    {
        $this->selectedPrId = $id;
        $this->showTimelinePanel = true;
    }

    public function closeTimeline(): void
    {
        $this->showTimelinePanel = false;
        $this->selectedPrId = null;
    }

    public function getViewData(): array
    {
        $terminalStatuses = self::TERMINAL_STATUSES;

        $query = PrHeader::with(['detail', 'detail.vessel', 'detail.items', 'items', 'requester'])
            ->whereIn('pr_status', $terminalStatuses);

        if ($this->statusFilter) {
            $query->where('pr_status', $this->statusFilter);
        }

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('pr_number', 'like', "%{$search}%")
                    ->orWhere('po_number', 'like', "%{$search}%")
                    ->orWhereHas('detail.items', function ($iq) use ($search) {
                        $iq->where('po_number', 'like', "%{$search}%");
                    })
                    ->orWhereHas('detail', function ($dq) use ($search) {
                        $dq->where('needs', 'like', "%{$search}%")
                            ->orWhere('title', 'like', "%{$search}%")
                            ->orWhereHas('vessel', fn($vq) => $vq->where('name', 'like', "%{$search}%"));
                    })
                    ->orWhereHas('requester', fn($rq) => $rq->where('name', 'like', "%{$search}%"));
            });
        }

        $prList = $query->latest()->paginate(15);

        // Timeline: fetch PrLog entries for the selected PR
        $selectedPr = null;
        $timeline = collect();

        if ($this->selectedPrId) {
            $selectedPr = PrHeader::with(['detail', 'detail.vessel', 'requester'])->find($this->selectedPrId);
            $timeline = PrLog::with(['user', 'role'])
                ->where('pr_header_id', $this->selectedPrId)
                ->oldest()
                ->get();

            // Ambil semua item log untuk batch_id pada timeline
            $batchIds = $timeline->pluck('batch_id')->filter()->unique();
            $itemLogsByTime = ItemLog::with('itemCategory')
                ->whereIn('batch_id', $batchIds)
                ->get()
                ->groupBy(fn($item) => $item->created_at ? $item->created_at->toDateTimeString() : '');

            $previousItems = collect();

            foreach ($timeline as $index => $log) {
                $timeKey = $log->created_at ? $log->created_at->toDateTimeString() : '';
                $currentItems = $itemLogsByTime->get($timeKey, collect());
                $diffs = [];

                if ($index > 0) {
                    $prevMapped = $previousItems->keyBy(fn($i) => $i->type . '|' . $i->size);
                    $currMapped = $currentItems->keyBy(fn($i) => $i->type . '|' . $i->size);

                    // Cek item yang berubah atau ditambahkan
                    foreach ($currMapped as $key => $currItem) {
                        if ($prevMapped->has($key)) {
                            $prevItem = $prevMapped->get($key);
                            $changes = [];
                            
                            if ((float)$currItem->quantity !== (float)$prevItem->quantity) {
                                $changes[] = "Qty: " . (float)$prevItem->quantity . " &rarr; " . (float)$currItem->quantity;
                            }
                            if ($currItem->unit !== $prevItem->unit) {
                                $changes[] = "Satuan: " . ($prevItem->unit ?? '-') . " &rarr; " . ($currItem->unit ?? '-');
                            }
                            if ((float)$currItem->remaining !== (float)$prevItem->remaining) {
                                $changes[] = "Sisa: " . ($prevItem->remaining ?? '-') . " &rarr; " . ($currItem->remaining ?? '-');
                            }
                            
                            if (!empty($changes)) {
                                $diffs[] = [
                                    'item_name' => $currItem->type . ($currItem->size ? ' (' . $currItem->size . ')' : ''),
                                    'status' => 'changed',
                                    'changes' => $changes
                                ];
                            }
                        } else {
                            $diffs[] = [
                                'item_name' => $currItem->type . ($currItem->size ? ' (' . $currItem->size . ')' : ''),
                                'status' => 'added',
                                'changes' => ["Ditambahkan (Qty: " . (float)$currItem->quantity . " " . ($currItem->unit ?? '') . ")"]
                            ];
                        }
                    }

                    // Cek item yang dihapus
                    foreach ($prevMapped as $key => $prevItem) {
                        if (!$currMapped->has($key)) {
                            $diffs[] = [
                                'item_name' => $prevItem->type . ($prevItem->size ? ' (' . $prevItem->size . ')' : ''),
                                'status' => 'removed',
                                'changes' => ["Dihapus"]
                            ];
                        }
                    }
                }

                $log->item_diffs = $diffs;
                $previousItems = $currentItems;
            }
        }

        $now = Carbon::now();

        return [
            'prList'     => $prList,
            'selectedPr' => $selectedPr,
            'timeline'   => $timeline,
            'statuses'   => collect($terminalStatuses)->mapWithKeys(fn($s) => [
                $s => PrStatusConstant::getStatuses()[$s] ?? $s,
            ])->all(),
            'stats' => [
                'total'     => PrHeader::whereIn('pr_status', $terminalStatuses)->count(),
                'converted' => PrHeader::where('pr_status', PrStatusConstant::CONVERTED_TO_PO)->count(),
                'rejected'  => PrHeader::where('pr_status', PrStatusConstant::REJECTED)->count(),
                'closed'    => PrHeader::where('pr_status', PrStatusConstant::CLOSED)->count(),
                'this_month' => PrHeader::whereIn('pr_status', $terminalStatuses)
                    ->where(function ($q) use ($now) {
                        $q->whereMonth('approved_at', $now->month)->whereYear('approved_at', $now->year);
                    })
                    ->orWhere(function ($q) use ($now) {
                        $q->whereIn('pr_status', [PrStatusConstant::REJECTED, PrStatusConstant::CLOSED])
                            ->whereMonth('updated_at', $now->month)->whereYear('updated_at', $now->year);
                    })
                    ->count(),
            ],
        ];
    }
}
