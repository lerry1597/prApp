<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\PrHeader;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\WithPagination;

class ProcessedPoList extends Page
{
    use WithPagination;

    public string $search = '';

    public bool $showDetailPanel = false;
    public ?int $selectedPrId = null;

    protected static ?string $navigationLabel = 'PR Sudah ke PO';
    protected static ?string $title = 'Daftar PR yang Sudah Dikonversi ke PO';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static string|\UnitEnum|null $navigationGroup = 'Procurement Officer';
    protected static ?int $navigationSort = 2;
    protected string $view = 'filament.app.pages.processed-po-list';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', RoleConstant::PROCUREMENT_OFFICER)->exists() ?? false;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public function getViewData(): array
    {
        $now = Carbon::now();

        $query = PrHeader::with(['detail', 'detail.vessel', 'detail.items', 'items', 'requester', 'approver'])
            ->where('pr_status', PrStatusConstant::CONVERTED_TO_PO);

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

        $prList = $query->latest('approved_at')->paginate(10);

        $selectedPr = null;
        if ($this->selectedPrId) {
            $selectedPr = PrHeader::with([
                'detail',
                'detail.vessel',
                'detail.items',
                'detail.items.itemCategory',
                'requester',
                'approver',
            ])->find($this->selectedPrId);
        }

        return [
            'prList'     => $prList,
            'selectedPr' => $selectedPr,
            'stats'      => [
                'total'      => PrHeader::where('pr_status', PrStatusConstant::CONVERTED_TO_PO)->count(),
                'this_month' => PrHeader::where('pr_status', PrStatusConstant::CONVERTED_TO_PO)
                    ->whereMonth('approved_at', $now->month)
                    ->whereYear('approved_at', $now->year)
                    ->count(),
                'this_week'  => PrHeader::where('pr_status', PrStatusConstant::CONVERTED_TO_PO)
                    ->whereBetween('approved_at', [
                        $now->copy()->startOfWeek(),
                        $now->copy()->endOfWeek(),
                    ])
                    ->count(),
            ],
        ];
    }

    public function openDetail(int $id): void
    {
        $this->selectedPrId = $id;
        $this->showDetailPanel = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailPanel = false;
        $this->selectedPrId = null;
    }
}
