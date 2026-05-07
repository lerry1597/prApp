<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\ItemCategory;
use App\Models\PrHeader;
use App\Models\PrLog;
use App\Models\ItemLog;
use App\Models\Role;
use App\Service\ConvertToPoService;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithPagination;

class ProcurementOfficerPrList extends Page
{
    use WithPagination;

    public string $search = '';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public ?string $statusFilter = '';

    public bool $showDetailPanel = false;
    public ?int $selectedPrId = null;
    public string $itemCategoryFilter = '';
    public string $itemSearch = '';

    public bool $showDiffModal = false;

    public bool $showApproveModal = false;
    public ?int $approvalPrId = null;
    public array $poNumbers = [];
    public string $bulkPoNumber = '';
    public array $approvalItems = [];
    public string $approveError = '';

    protected static ?string $navigationLabel = 'Daftar Pengajuan Barang';
    protected static ?string $title = 'Daftar Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string|\UnitEnum|null $navigationGroup = 'Procurement Officer';
    protected static ?int $navigationSort = 1;
    protected string $view = 'filament.app.pages.procurement-officer-pr-list';

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
        $this->startDate = null;
        $this->endDate = null;
        $this->statusFilter = '';
        $this->resetPage();
        $this->dispatch('po-date-filters-reset');
    }

    public function getViewData(): array
    {
        $procurementRole = Role::where('name', RoleConstant::PROCUREMENT_OFFICER)->firstOrFail();

        $query = PrHeader::with(['detail', 'detail.vessel', 'detail.items', 'currentRole', 'requester'])
            ->visibleToUser(auth()->user());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('pr_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('detail', function ($dq) {
                        $dq->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('needs', 'like', '%' . $this->search . '%')
                            ->orWhere('document_no', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('requester', function ($rq) {
                        $rq->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('pr_status', $this->statusFilter);
        }

        if ($this->startDate) {
            $startAt = Carbon::parse($this->startDate);
            if (! str_contains((string) $this->startDate, ':')) {
                $startAt = $startAt->startOfDay();
            }
            $query->where('created_at', '>=', $startAt);
        }

        if ($this->endDate) {
            $endAt = Carbon::parse($this->endDate);
            if (! str_contains((string) $this->endDate, ':')) {
                $endAt = $endAt->endOfDay();
            }
            $query->where('created_at', '<=', $endAt);
        }

        $prList = $query->latest()->paginate(10);

        $selectedPr = null;
        $filteredItems = collect();
        $itemCategories = collect();
        if ($this->selectedPrId) {
            $selectedPr = PrHeader::with([
                'detail',
                'detail.vessel',
                'detail.items',
                'detail.items.itemCategory',
                'currentRole',
                'requester',
            ])->find($this->selectedPrId);

            if ($selectedPr) {
                $allItems = $selectedPr->detail?->items ?? collect();

                // Collect unique categories from the PR's items
                $itemCategories = $allItems
                    ->map(fn($i) => $i->itemCategory)
                    ->filter()
                    ->unique('id')
                    ->values();

                $filteredItems = $allItems->when(
                    $this->itemCategoryFilter,
                    fn($c) => $c->filter(fn($i) => $i->item_category_id == $this->itemCategoryFilter)
                )->when(
                    $this->itemSearch,
                    fn($c) => $c->filter(function ($i) {
                        $q = strtolower($this->itemSearch);
                        return str_contains(strtolower($i->type ?? ''), $q)
                            || str_contains(strtolower($i->description ?? ''), $q)
                            || str_contains(strtolower($i->size ?? ''), $q)
                            || str_contains(strtolower($i->itemCategory?->name ?? ''), $q);
                    })
                )->values();

                // Dapatkan semua log PR untuk perbandingan tabel
                $allLogs = PrLog::where('pr_header_id', $this->selectedPrId)->oldest()->get();
                $itemDiffs = [];
                $diffSteps = [];
                $diffRows = [];

                if ($allLogs->count() >= 2) {
                    $firstLog = $allLogs->first();
                    $lastLog = $allLogs->last();

                    // Timeline steps
                    $diffSteps = [
                        [
                            'label' => 'Pengajuan Awal',
                            'time'  => $firstLog->created_at?->timezone('Asia/Jakarta')->format('d/m/y H:i'),
                        ],
                        [
                            'label' => 'Persetujuan ke-' . ($allLogs->count() - 1),
                            'time'  => $lastLog->created_at?->timezone('Asia/Jakarta')->format('d/m/y H:i'),
                        ],
                    ];

                    // Ambil snapshot awal
                    $firstTimeKey = $firstLog->created_at?->toDateTimeString();
                    $initialItems = collect();
                    if ($firstTimeKey) {
                        $initialItems = ItemLog::with('itemCategory')
                            ->where('batch_id', $firstLog->batch_id)
                            ->get()
                            ->filter(fn($i) => $i->created_at && $i->created_at->toDateTimeString() === $firstTimeKey)
                            ->keyBy(fn($i) => $i->type . '|' . $i->size);
                    }

                    // Ambil snapshot terbaru
                    $lastTimeKey = $lastLog->created_at?->toDateTimeString();
                    $latestItems = collect();
                    if ($lastTimeKey) {
                        $latestItems = ItemLog::with('itemCategory')
                            ->where('batch_id', $lastLog->batch_id)
                            ->get()
                            ->filter(fn($i) => $i->created_at && $i->created_at->toDateTimeString() === $lastTimeKey)
                            ->keyBy(fn($i) => $i->type . '|' . $i->size);
                    }

                    // Jika tidak ada log item terbaru, gunakan item aktif saat ini
                    if ($latestItems->isEmpty()) {
                        $latestItems = $filteredItems->keyBy(fn($i) => $i->type . '|' . $i->size);
                    }

                    // Gabungkan semua key unik
                    $allKeys = $initialItems->keys()->merge($latestItems->keys())->unique();
                    $no = 1;

                    foreach ($allKeys as $key) {
                        $init = $initialItems->get($key);
                        $latest = $latestItems->get($key);
                        $source = $init ?? $latest;

                        $initQty = $init ? (float)$init->quantity : null;
                        $latestQty = $latest ? (float)$latest->quantity : null;

                        // Tentukan status perubahan qty
                        $qtyStatus = 'same'; // same, up, down, added, removed
                        if ($initQty === null) {
                            $qtyStatus = 'added';
                        } elseif ($latestQty === null) {
                            $qtyStatus = 'removed';
                        } elseif ($latestQty > $initQty) {
                            $qtyStatus = 'up';
                        } elseif ($latestQty < $initQty) {
                            $qtyStatus = 'down';
                        }

                        $diffRows[] = [
                            'no'         => $no++,
                            'category'   => $source->itemCategory?->name ?? '—',
                            'type'       => $source->type ?? '—',
                            'size'       => $source->size ?? '—',
                            'unit'       => $source->unit ?? '—',
                            'init_qty'   => $initQty,
                            'latest_qty' => $latestQty,
                            'qty_status' => $qtyStatus,
                        ];
                    }

                    // Juga buat itemDiffs sederhana agar tombol tetap muncul
                    foreach ($diffRows as $row) {
                        if ($row['qty_status'] !== 'same') {
                            $itemDiffs[] = ['status' => $row['qty_status']];
                        }
                    }
                }
            }
        }

        return [
            'prList'         => $prList,
            'selectedPr'     => $selectedPr,
            'filteredItems'  => $filteredItems,
            'itemCategories' => $itemCategories,
            'itemDiffs'      => $itemDiffs ?? [],
            'diffSteps'      => $diffSteps ?? [],
            'diffRows'       => $diffRows ?? [],
            'statuses'       => PrStatusConstant::getStatuses(),
            'stats'          => [
                'total'     => PrHeader::whereNotNull('current_role_id')->whereNotNull('current_step_id')->where('current_role_id', $procurementRole->id)->count(),
                'approved'  => PrHeader::whereNotNull('current_role_id')->whereNotNull('current_step_id')->where('current_role_id', $procurementRole->id)->where('pr_status', PrStatusConstant::APPROVED)->count(),
                'converted' => PrHeader::where('pr_status', PrStatusConstant::CONVERTED_TO_PO)->count(),
            ],
        ];
    }

    public function openDetail(int $id): void
    {
        $this->selectedPrId = $id;
        $this->itemCategoryFilter = '';
        $this->itemSearch = '';
        $this->showDetailPanel = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailPanel = false;
        $this->selectedPrId = null;
        $this->itemCategoryFilter = '';
        $this->itemSearch = '';
        $this->showDiffModal = false;
    }

    public function openDiffModal(): void
    {
        $this->showDiffModal = true;
    }

    public function closeDiffModal(): void
    {
        $this->showDiffModal = false;
    }

    public function openApproveModal(int $id): void
    {
        $this->approvalPrId = $id;
        $this->approveError = '';
        $this->bulkPoNumber = '';

        // Load items for this PR
        $header = PrHeader::with('items.itemCategory')->find($id);
        $items = $header ? $header->items()->orderBy('id')->get() : collect();

        $this->approvalItems = $items->map(fn($item) => [
            'id'        => $item->id,
            'category'  => $item->itemCategory?->name ?? '—',
            'type'      => $item->type,
            'size'      => $item->size,
            'qty'       => (float) $item->quantity,
            'unit'      => $item->unit,
            'po_number' => $item->po_number ?? '',
        ])->toArray();

        // Pre-fill poNumbers from existing data
        $this->poNumbers = [];
        foreach ($this->approvalItems as $item) {
            $this->poNumbers[$item['id']] = $item['po_number'];
        }

        $this->showApproveModal = true;
    }

    public function closeApproveModal(): void
    {
        $this->showApproveModal = false;
        $this->approvalPrId = null;
        $this->poNumbers = [];
        $this->bulkPoNumber = '';
        $this->approvalItems = [];
        $this->approveError = '';
    }

    public function applyBulkPo(): void
    {
        $bulk = trim($this->bulkPoNumber);
        if ($bulk === '') return;

        foreach ($this->approvalItems as $item) {
            // Hanya isi yang belum punya PO
            if (empty($this->poNumbers[$item['id']] ?? '')) {
                $this->poNumbers[$item['id']] = $bulk;
            }
        }
    }

    public function confirmApprove(): void
    {
        $this->approveError = '';

        if (! $this->approvalPrId) {
            $this->approveError = 'Data PR tidak valid.';
            return;
        }

        // Filter hanya yang diisi
        $filled = collect($this->poNumbers)
            ->filter(fn($po) => is_string($po) && trim($po) !== '');

        if ($filled->isEmpty()) {
            $this->approveError = 'Minimal satu item harus diisi nomor PO.';
            return;
        }

        $header = PrHeader::find($this->approvalPrId);
        if (! $header) {
            $this->approveError = 'PR tidak ditemukan.';
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        $result = app(ConvertToPoService::class)->execute($header, $this->poNumbers, $user);

        if (! $result['ok']) {
            $this->approveError = $result['title'] . ': ' . $result['message'];
            return;
        }

        $allAssigned = $result['all_assigned'] ?? false;
        $poList = $filled->unique()->implode(', ');

        // Tutup kedua modal agar notifikasi terlihat jelas di halaman utama
        $this->closeApproveModal();
        $this->closeDetail();

        if ($allAssigned) {
            Notification::make()
                ->success()
                ->title('Konversi ke PO Selesai')
                ->body('Semua item telah di-assign PO. PR selesai diproses.')
                ->send();
        } else {
            Notification::make()
                ->success()
                ->title('PO Berhasil Disimpan')
                ->body('Nomor PO berhasil disimpan untuk sebagian item. PR masih menunggu item lainnya.')
                ->send();
        }
    }
}
