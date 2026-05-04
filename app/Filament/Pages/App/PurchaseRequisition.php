<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Models\ItemLog;
use App\Models\PrHeader;
use Filament\Pages\Page;
use Livewire\WithPagination;

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

    protected static ?string $navigationLabel = 'Daftar Pengajuan PR';
    protected static ?string $title = 'Daftar Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
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

    public function getViewData(): array
    {
        $user = auth()->user();

        $query = PrHeader::with(['detail', 'detail.vessel', 'currentRole'])
            ->where('requester_id', $user?->id)
            ->whereNotIn('pr_status', [
                PrStatusConstant::REJECTED,
                PrStatusConstant::CLOSED,
            ])
            ->where(function ($q) {
                // Tampilkan PR yang belum mencapai step terakhir workflow.
                // Jika current_step_id masih null, tetap dianggap masih proses.
                $q->whereNull('current_step_id')
                    ->orWhereRaw(
                        "EXISTS (
                            SELECT 1
                            FROM approval_steps s
                            WHERE s.id = pr_headers.current_step_id
                              AND s.deleted_at IS NULL
                              AND s.step_order < (
                                  SELECT MAX(s2.step_order)
                                  FROM approval_steps s2
                                  WHERE s2.approval_workflow_id = pr_headers.approval_workflow_id
                                    AND s2.deleted_at IS NULL
                              )
                        )"
                    );
            });

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
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $prList = $query->latest()
            ->paginate(3);

        return [
            'prList' => $prList,
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
     * Muat riwayat perubahan item berdasarkan batch_id PR,
     * lalu kelompokkan per snapshot (setiap approval = satu set insert baru).
     */
    public function showItemHistory(): void
    {
        if (! $this->selectedPr) {
            return;
        }

        $batchId = $this->selectedPr->batch_id;

        // Ambil semua item log untuk batch ini, urutkan dari yang paling lama
        $rows = ItemLog::where('batch_id', $batchId)
            ->with('itemCategory')
            ->orderBy('id')
            ->get();

        if ($rows->isEmpty()) {
            $this->itemSnapshots = [];
            $this->showHistoryModal = true;
            return;
        }

        // Kelompokkan per snapshot berdasarkan created_at (dibulatkan per detik).
        // Semua insert dalam satu transaksi akan punya created_at yang sama/sangat dekat.
        $grouped = $rows->groupBy(fn($r) => $r->created_at?->format('Y-m-d H:i:s') ?? 'unknown');

        $snapshots = [];
        $index = 0;
        foreach ($grouped as $timestamp => $items) {
            $itemsMap = [];
            foreach ($items as $item) {
                $key = $item->no ?? ($item->type . '|' . $item->size);
                $itemsMap[$key] = [
                    'no'           => $item->no,
                    'category'     => $item->itemCategory?->name ?? '—',
                    'type'         => $item->type,
                    'size'         => $item->size,
                    'quantity'     => $item->quantity,
                    'unit'         => $item->unit,
                    'remaining'    => $item->remaining,
                ];
            }
            $snapshots[] = [
                'label'      => $index === 0 ? 'Pengajuan Awal' : 'Persetujuan ke-' . $index,
                'created_at' => $timestamp,
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
