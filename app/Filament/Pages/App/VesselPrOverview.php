<?php

namespace App\Filament\Pages\App;

use App\Constants\PrStatusConstant;
use App\Constants\RoleConstant;
use App\Models\PrHeader;
use App\Models\Vessel;
use Filament\Pages\Page;

class VesselPrOverview extends Page
{
    public string $search = '';
    public ?int $selectedVesselId = null;
    public ?int $selectedPrId = null;

    protected static ?string $navigationLabel = 'Rekap per Kapal';
    protected static ?string $title = 'Rekap Pengajuan PR per Kapal';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';
    protected static string|\UnitEnum|null $navigationGroup = 'Procurement Officer';
    protected static ?int $navigationSort = 3;
    protected string $view = 'filament.app.pages.vessel-pr-overview';

    public static function canAccess(): bool
    {
        return auth()->user()?->roles()->where('name', RoleConstant::PROCUREMENT_OFFICER)->exists() ?? false;
    }

    public function updatedSearch(): void
    {
        $this->selectedVesselId = null;
    }

    public function selectVessel(int $id): void
    {
        $this->selectedVesselId = ($this->selectedVesselId === $id) ? null : $id;
        $this->selectedPrId = null;
    }

    public function closeVesselPanel(): void
    {
        $this->selectedVesselId = null;
        $this->selectedPrId = null;
    }

    public function openPrItems(int $prId): void
    {
        $this->selectedPrId = $prId;
    }

    public function closePrItems(): void
    {
        $this->selectedPrId = null;
    }

    public function getViewData(): array
    {
        $vesselQuery = Vessel::query()
            ->whereHas('prDetails', fn($q) => $q->whereHas('header'))
            ->withCount([
                'prDetails as total_pr',
                'prDetails as in_progress_pr' => fn($q) => $q->whereHas('header', fn($h) => $h
                    ->whereNotNull('current_role_id')
                    ->whereNotNull('current_step_id')
                    ->whereNotIn('pr_status', [PrStatusConstant::REJECTED, PrStatusConstant::CLOSED])),
                'prDetails as converted_pr' => fn($q) => $q->whereHas('header', fn($h) => $h
                    ->where('pr_status', PrStatusConstant::CONVERTED_TO_PO)),
                'prDetails as rejected_pr' => fn($q) => $q->whereHas('header', fn($h) => $h
                    ->whereIn('pr_status', [PrStatusConstant::REJECTED, PrStatusConstant::CLOSED])),
            ])
            ->orderByDesc('total_pr');

        if ($this->search) {
            $vesselQuery->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
        }

        $vessels = $vesselQuery->get();

        $selectedVessel = null;
        $vesselPrs = collect();
        $selectedPr = null;

        if ($this->selectedVesselId) {
            $selectedVessel = Vessel::find($this->selectedVesselId);
            if ($selectedVessel) {
                $vesselPrs = PrHeader::with(['detail', 'requester', 'currentRole'])
                    ->whereHas('detail', fn($q) => $q->where('vessel_id', $this->selectedVesselId))
                    ->latest()
                    ->get();
            }
        }

        if ($this->selectedPrId) {
            $selectedPr = PrHeader::with(['detail.items.itemCategory', 'requester'])->find($this->selectedPrId);
        }

        $totalPrAll = $vessels->sum('total_pr');

        return [
            'vessels'         => $vessels,
            'selectedVessel'  => $selectedVessel,
            'vesselPrs'       => $vesselPrs,
            'selectedPr'      => $selectedPr,
            'stats'           => [
                'total_vessels' => $vessels->count(),
                'total_pr'      => $totalPrAll,
                'in_progress'   => $vessels->sum('in_progress_pr'),
                'converted'     => $vessels->sum('converted_pr'),
            ],
        ];
    }
}
