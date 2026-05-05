<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Models\PrHeader;
use BackedEnum;
use UnitEnum;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

class ApprovedPrList extends Page
{
    use WithPagination;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'PR Disetujui';

    protected static ?string $title = 'Daftar PR Disetujui';

    protected static ?string $slug = 'approved-pr-list';

    protected string $view = 'filament.app.pages.approved-pr-list';

    protected static ?int $navigationSort = 3;

    public string $search = '';

    // Modal detail
    public bool $showDetailModal = false;
    public ?PrHeader $selectedPr = null;

    // Stats
    public int $totalApproved = 0;

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', \App\Constants\RoleConstant::TECHNICAL_APPROVER)->exists() ?? false;
    }

    public function mount(): void
    {
        $this->updateStats();
    }

    public function updateStats(): void
    {
        $this->totalApproved = PrHeader::where('pr_status', PrStatusConstant::APPROVED)->count();
    }

    public function getPrListProperty()
    {
        return PrHeader::query()
            ->where('pr_status', PrStatusConstant::APPROVED)
            ->with(['requester', 'detail.vessel', 'approver'])
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('pr_number', 'like', "%{$this->search}%")
                        ->orWhereHas('requester', fn($r) => $r->where('name', 'like', "%{$this->search}%"))
                        ->orWhereHas('detail', fn($d) => $d->where('needs', 'like', "%{$this->search}%")
                            ->orWhereHas('vessel', fn($v) => $v->where('name', 'like', "%{$this->search}%")));
                });
            })
            ->orderBy('approved_at', 'desc')
            ->paginate(10);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function openDetail(int $id): void
    {
        $this->selectedPr = PrHeader::with(['requester', 'detail.vessel', 'approver', 'items.itemCategory'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetail(): void
    {
        $this->showDetailModal = false;
        $this->selectedPr = null;
    }
}
