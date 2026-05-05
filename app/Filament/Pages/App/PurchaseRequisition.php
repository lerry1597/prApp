<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Models\ItemLog;
use App\Models\PrHeader;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\WithPagination;
use UnitEnum;

class PurchaseRequisition extends Page
{
    use WithPagination;

    public $search = '';
    public $startDate = null;
    public $endDate = null;

    public $showDetailModal = false;
    public $selectedPr = null;

    public $showHistoryModal = false;
    public array $itemSnapshots = [];  // [ ['label'=>'...','created_at'=>'...', 'items'=>[no=>row]] ]

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
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function resetDateFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->resetPage();
        $this->dispatch('pr-date-filters-reset');
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        $query = PrHeader::with(['detail', 'detail.vessel', 'items', 'currentRole'])
            ->where('requester_id', $user?->id)
            ->whereNotIn('pr_status', [
                PrStatusConstant::REJECTED,
                PrStatusConstant::CLOSED,
            ])
            ->whereNotNull('current_step_id')
            ->whereNotNull('current_role_id');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('pr_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('detail', function ($dq) {
                        $dq->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('needs', 'like', '%' . $this->search . '%')
                            ->orWhere('document_no', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->startDate) {
            $startAt = Carbon::parse($this->startDate);

            // Backward compatible: date-only values are treated as start of day.
            if (! str_contains((string) $this->startDate, ':')) {
                $startAt = $startAt->startOfDay();
            }

            $query->where('created_at', '>=', $startAt);
        }

        if ($this->endDate) {
            $endAt = Carbon::parse($this->endDate);

            // Backward compatible: date-only values are treated as end of day.
            if (! str_contains((string) $this->endDate, ':')) {
                $endAt = $endAt->endOfDay();
            }

            $query->where('created_at', '<=', $endAt);
        }

        $statusCounts = (clone $query)
            ->selectRaw('pr_status, COUNT(*) as total')
            ->groupBy('pr_status')
            ->pluck('total', 'pr_status');

        $prSummary = [
            'total' => (clone $query)->count(),
            'waiting' => (int) ($statusCounts[PrStatusConstant::WAITING_APPROVAL] ?? 0) + (int) ($statusCounts[PrStatusConstant::PENDING] ?? 0),
            'submitted' => (int) ($statusCounts[PrStatusConstant::SUBMITTED] ?? 0),
            'approved' => (int) ($statusCounts[PrStatusConstant::APPROVED] ?? 0),
        ];

        $prList = $query->latest()
            ->paginate(3);

        return [
            'prList' => $prList,
            'prSummary' => $prSummary,
        ];
    }

    public function showDetail($id)
    {
        $this->selectedPr = PrHeader::with(['detail', 'detail.vessel', 'detail.items', 'detail.items.itemCategory', 'currentRole'])
            ->find($id);

        if ($this->selectedPr) {
            $this->showDetailModal = true;
        }
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->selectedPr = null;
    }

    /**
     * Muat riwayat perubahan item berdasarkan batch_id PR dengan mengambil
     * snapshot data barang langsung dari field 'payload' di tabel pr_logs.
     */
    public function showItemHistory(): void
    {
        if (! $this->selectedPr) {
            return;
        }

        $batchId = $this->selectedPr->batch_id;

        // Ambil semua log PR untuk batch ini, urutkan dari yang terlama
        $logs = \App\Models\PrLog::where('batch_id', $batchId)
            ->whereNotNull('payload')
            ->orderBy('id')
            ->get();

        if ($logs->isEmpty()) {
            $this->itemSnapshots = [];
            $this->showHistoryModal = true;
            return;
        }

        // Ambil mapping kategori untuk menamai item_category_id
        $categories = \App\Models\ItemCategory::pluck('name', 'id')->toArray();

        $snapshots = [];
        $index = 0;
        foreach ($logs as $log) {
            $payload = $log->payload;

            // Kita fokus pada data 'items' yang direkam di dalam payload
            if (!isset($payload['items']) || !is_array($payload['items'])) {
                continue;
            }

            $itemsMap = [];
            foreach ($payload['items'] as $item) {
                // Di dalam payload bentuknya adalah array asosiatif
                $type = $item['type'] ?? '';
                $size = $item['size'] ?? '';
                $no = $item['no'] ?? null;
                $categoryId = $item['item_category_id'] ?? null;

                $key = $no ?? ($type . '|' . $size);

                $itemsMap[$key] = [
                    'no'           => $no,
                    'category'     => $categories[$categoryId] ?? '—',
                    'type'         => $type,
                    'size'         => $size,
                    'quantity'     => $item['quantity'] ?? 0,
                    'unit'         => $item['unit'] ?? '—',
                    'remaining'    => $item['remaining'] ?? 0,
                    'po_number'    => $item['po_number'] ?? null,
                ];
            }

            $snapshots[] = [
                'label'      => $index === 0 ? 'Pengajuan Awal' : 'Persetujuan ke-' . $index,
                'created_at' => $log->created_at?->format('Y-m-d H:i:s') ?? 'unknown',
                'items'      => $itemsMap,
            ];
            $index++;
        }

        $this->itemSnapshots = $snapshots;
        $this->showHistoryModal = true;
    }

    public function closeItemHistory(): void
    {
        $this->showHistoryModal = false;
        $this->itemSnapshots = [];
    }
}
