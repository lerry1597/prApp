<?php

namespace App\Filament\Pages\Admin;

use App\Models\Vessel;
use App\Models\VesselCategory;
use App\Models\Company;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Collection;
use BackedEnum;
use UnitEnum;

class VesselSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Kelola Kapal';
    protected static ?string $title = 'Kelola Kapal & Kategori';
    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.admin.vessel-settings';

    public function getVesselsProperty(): Collection
    {
        return Vessel::with(['vesselCategory', 'company'])->get();
    }

    public function getCategoriesProperty(): Collection
    {
        return VesselCategory::withCount('vessels')->get();
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Vessel Settings')
                    ->label('')
                    ->tabs([
                        Tab::make('Daftar Kapal')
                            ->icon('lucide-ship')
                            // ->badge($this->vessels->count())
                            ->schema([
                                Grid::make(['default' => 1, 'md' => 2, 'xl' => 3])
                                    ->schema($this->getVesselSchema()),
                            ]),
                        Tab::make('Kategori kapal')
                            ->icon('lucide-layers')
                            // ->badge($this->categories->count())
                            ->schema([
                                Grid::make(['default' => 1, 'md' => 2, 'xl' => 3])
                                    ->schema($this->getCategorySchema()),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getVesselSchema(): array
    {
        return $this->vessels->map(function (Vessel $vessel) {
            return Section::make($vessel->name)
                ->icon('lucide-ship')
                ->description($vessel->vesselCategory->name ?? 'Uncategorized')
                ->headerActions([
                    Action::make('editVessel')
                        ->icon('heroicon-m-pencil-square')
                        ->color('gray')
                        ->iconButton()
                        ->action(fn() => $this->mountAction('editVessel', ['record' => $vessel->id])),
                    Action::make('deleteVessel')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->iconButton()
                        ->action(fn() => $this->mountAction('deleteVessel', ['record' => $vessel->id])),
                ])
                ->schema([
                    Group::make([
                        Text::make('IMO: ' . ($vessel->code ?: '-'))
                            ->color('gray')
                            ->size('xs'),
                        Text::make($vessel->company->name ?? 'N/A')
                            ->icon('heroicon-m-building-office')
                            ->color('gray')
                            ->size('xs'),
                        // Text::make($vessel->flag ?: 'International')
                        // ->badge()
                        // ->color('primary'),
                        Text::make($vessel->vesselCategory->name ?? 'Uncategorized')
                            ->icon('lucide-layers')
                            ->badge()
                            ->color('warning'),
                    ])->gap(),
                ])
                ->columnSpan(1);
        })->toArray();
    }

    protected function getCategorySchema(): array
    {
        return $this->categories->map(function (VesselCategory $category) {
            return Section::make($category->name)
                ->icon('lucide-ship')
                ->headerActions([
                    Action::make('editCategory')
                        ->icon('heroicon-m-pencil-square')
                        ->color('gray')
                        ->iconButton()
                        ->action(fn() => $this->mountAction('editCategory', ['record' => $category->id])),
                    Action::make('deleteCategory')
                        ->icon('lucide-trash-2')
                        ->color('danger')
                        ->iconButton()
                        ->action(fn() => $this->mountAction('deleteCategory', ['record' => $category->id])),
                ])
                ->schema([
                    Group::make([
                        Text::make('Kode: ' . strtoupper($category->code ?: '-'))
                            ->color('gray')
                            ->size('xs'),
                        Text::make($category->vessels_count . ' Kapal Terdaftar')
                            ->badge()
                            ->color('warning'),
                    ])->gap(),
                ])
                ->columnSpan(1);
        })->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createVessel')
                ->label('Tambah Kapal')
                ->icon('heroicon-m-plus')
                ->form([
                    TextInput::make('name')->required(),
                    TextInput::make('code')->required()->unique(Vessel::class, 'code'),
                    Select::make('vessel_category_id')
                        ->label('Kategori')
                        ->options(VesselCategory::pluck('name', 'id'))
                        ->required(),
                    Select::make('company_id')
                        ->label('Perusahaan')
                        ->options(Company::pluck('name', 'id'))
                        ->required(),
                    TextInput::make('flag'),
                    Textarea::make('description'),
                ])
                ->action(fn(array $data) => Vessel::create($data)),

            Action::make('createCategory')
                ->label('Tambah Kategori')
                ->icon('heroicon-m-plus')
                ->color('warning')
                ->form([
                    TextInput::make('name')->required(),
                    TextInput::make('code'),
                    Textarea::make('description'),
                ])
                ->action(fn(array $data) => VesselCategory::create($data)),
        ];
    }

    public function editVesselAction(): Action
    {
        return Action::make('editVessel')
            ->form([
                TextInput::make('name')->required(),
                TextInput::make('code')->required(),
                Select::make('vessel_category_id')
                    ->options(VesselCategory::pluck('name', 'id'))
                    ->required(),
                Select::make('company_id')
                    ->options(Company::pluck('name', 'id'))
                    ->required(),
                TextInput::make('flag'),
            ])
            ->fillForm(fn(Vessel $record) => $record->toArray())
            ->action(fn(Vessel $record, array $data) => $record->update($data));
    }

    public function deleteVesselAction(): Action
    {
        return Action::make('deleteVessel')
            ->requiresConfirmation()
            ->action(fn(Vessel $record) => $record->delete());
    }

    public function editCategoryAction(): Action
    {
        return Action::make('editCategory')
            ->form([
                TextInput::make('name')->required(),
                TextInput::make('code'),
            ])
            ->fillForm(fn(VesselCategory $record) => $record->toArray())
            ->action(fn(VesselCategory $record, array $data) => $record->update($data));
    }

    public function deleteCategoryAction(): Action
    {
        return Action::make('deleteCategory')
            ->requiresConfirmation()
            ->action(fn(VesselCategory $record) => $record->delete());
    }
}
