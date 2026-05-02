<?php

namespace App\Filament\Pages\App;

use App\Models\PrHeader;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Pages\Page;

use Livewire\WithPagination;

class PurchaseRequisition extends Page
{
    use WithPagination;
    
    public $search = '';
    public $startDate = null;
    public $endDate = null;

    protected static ?string $navigationLabel = 'Daftar Pengajuan PR';
    protected static ?string $title = 'Daftar Pengajuan Barang';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected string $view = 'filament.app.pages.purchase-requisition';

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
            ->where('requester_id', $user?->id);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('pr_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('detail', function($dq) {
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
}
