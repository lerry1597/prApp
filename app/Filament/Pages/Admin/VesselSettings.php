<?php

namespace App\Filament\Pages\Admin;

use App\Models\Vessel;
use App\Models\VesselCategory;
use App\Models\Company;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use UnitEnum;

use BackedEnum;

class VesselSettings extends Page implements HasForms, HasActions
{
    use InteractsWithForms, InteractsWithActions;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Vessel';

    protected static ?string $title = 'Kelola Kapal & Kategori';

    protected static ?string $slug = 'vessel-settings';

    protected static ?int $navigationSort = 110;

    protected string $view = 'filament.pages.admin.vessel-settings';

    public $activeTab = 'vessels';

    // State for Vessels
    public $vessels = [];
    public $categories = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->vessels = Vessel::with(['company', 'vesselCategory'])->get();
        $this->categories = VesselCategory::withCount('vessels')->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make('createVessel')
                ->label('Tambah Kapal')
                ->model(Vessel::class)
                ->form([
                    Select::make('company_id')
                        ->label('Perusahaan')
                        ->options(Company::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Select::make('vessel_category_id')
                        ->label('Kategori')
                        ->options(VesselCategory::pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    TextInput::make('name')
                        ->label('Nama Kapal')
                        ->required(),
                    TextInput::make('code')
                        ->label('Kode/IMO')
                        ->unique(ignoreRecord: true),
                    TextInput::make('vessel_type')
                        ->label('Tipe (Manual)'),
                    TextInput::make('flag')
                        ->label('Bendera'),
                    Textarea::make('description')
                        ->label('Deskripsi'),
                ])
                ->action(function (array $data) {
                    Vessel::create($data);
                    $this->loadData();
                    Notification::make()->title('Kapal berhasil ditambahkan')->success()->send();
                }),

            CreateAction::make('createCategory')
                ->label('Tambah Kategori')
                ->model(VesselCategory::class)
                ->form([
                    TextInput::make('name')
                        ->label('Nama Kategori')
                        ->required(),
                    TextInput::make('code')
                        ->label('Kode Kategori')
                        ->unique(ignoreRecord: true),
                    Textarea::make('description')
                        ->label('Deskripsi'),
                ])
                ->action(function (array $data) {
                    VesselCategory::create($data);
                    $this->loadData();
                    Notification::make()->title('Kategori berhasil ditambahkan')->success()->send();
                }),
        ];
    }

    public function editVesselAction(): Action
    {
        return Action::make('editVessel')
            ->record(fn (array $arguments) => Vessel::find($arguments['record']))
            ->form([
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->options(Company::pluck('name', 'id'))
                    ->required(),
                Select::make('vessel_category_id')
                    ->label('Kategori')
                    ->options(VesselCategory::pluck('name', 'id'))
                    ->required(),
                TextInput::make('name')->required(),
                TextInput::make('code'),
                TextInput::make('vessel_type'),
                TextInput::make('flag'),
                Textarea::make('description'),
            ])
            ->action(function (Vessel $record, array $data): void {
                $record->update($data);
                $this->loadData();
                Notification::make()->title('Kapal berhasil diperbarui')->success()->send();
            });
    }

    public function editCategoryAction(): Action
    {
        return Action::make('editCategory')
            ->record(fn (array $arguments) => VesselCategory::find($arguments['record']))
            ->form([
                TextInput::make('name')->required(),
                TextInput::make('code'),
                Textarea::make('description'),
            ])
            ->action(function (VesselCategory $record, array $data): void {
                $record->update($data);
                $this->loadData();
                Notification::make()->title('Kategori berhasil diperbarui')->success()->send();
            });
    }

    public function deleteVesselAction(): Action
    {
        return Action::make('deleteVessel')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (array $arguments) {
                Vessel::find($arguments['record'])?->delete();
                $this->loadData();
                Notification::make()->title('Kapal berhasil dihapus')->danger()->send();
            });
    }

    public function deleteCategoryAction(): Action
    {
        return Action::make('deleteCategory')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (array $arguments) {
                $category = VesselCategory::find($arguments['record']);
                if ($category && $category->vessels()->count() > 0) {
                    Notification::make()->title('Gagal dihapus')->body('Kategori ini masih memiliki kapal terkait.')->danger()->send();
                    return;
                }
                $category?->delete();
                $this->loadData();
                Notification::make()->title('Kategori berhasil dihapus')->danger()->send();
            });
    }
}
