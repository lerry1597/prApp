<?php

namespace App\Filament\Pages\Admin;

use App\Models\Vessel;
use App\Models\VesselCategory;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
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
use Filament\Schemas\Components\Actions;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Collection;
use BackedEnum;
use UnitEnum;

class VesselSettings extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    #[\Livewire\Attributes\Url(as: 'v_search')]
    public string $vesselSearch = '';

    #[\Livewire\Attributes\Url(as: 'c_search')]
    public string $categorySearch = '';

    #[\Livewire\Attributes\Url(as: 'v_cat')]
    public string $vesselCategoryFilter = '';

    #[\Livewire\Attributes\Url(as: 'v_comp')]
    public string $vesselCompanyFilter = '';

    #[\Livewire\Attributes\Url(as: 'v_page')]
    public int $vPage = 1;

    #[\Livewire\Attributes\Url(as: 'c_page')]
    public int $cPage = 1;

    public function nextVesselPage(): void
    {
        $this->vPage++;
        unset($this->cachedSchemas['infolist']);
    }

    public function previousVesselPage(): void
    {
        if ($this->vPage > 1) {
            $this->vPage--;
            unset($this->cachedSchemas['infolist']);
        }
    }

    public function nextCategoryPage(): void
    {
        $this->cPage++;
        unset($this->cachedSchemas['infolist']);
    }

    public function previousCategoryPage(): void
    {
        if ($this->cPage > 1) {
            $this->cPage--;
            unset($this->cachedSchemas['infolist']);
        }
    }


    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Kelola Kapal';
    protected static ?string $title = 'Kelola Kapal & Kategori';
    protected static string|BackedEnum|null $navigationIcon = null;

    protected string $view = 'filament.pages.admin.vessel-settings';

    public function getVesselsProperty(): Collection
    {
        return $this->baseVesselQuery()->with(['vesselCategory', 'company'])->get();
    }

    protected function baseVesselQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = Vessel::query();
        $user  = Auth::user();

        if ($user && ! $user->isGlobalAdmin()) {
            $query->whereIn('company_id', $user->accessibleCompanyIds());
        }

        return $query;
    }

    public function getCategoriesProperty(): Collection
    {
        return VesselCategory::withCount('vessels')->get();
    }

    public function infolist(Schema $schema): Schema
    {
        // Dynamic count for Vessels
        $vesselQuery = $this->baseVesselQuery();
        if (!empty($this->vesselSearch)) {
            $vesselQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->vesselSearch . '%')
                  ->orWhere('code', 'like', '%' . $this->vesselSearch . '%');
            });
        }
        if (!empty($this->vesselCategoryFilter)) {
            $vesselQuery->where('vessel_category_id', $this->vesselCategoryFilter);
        }
        if (!empty($this->vesselCompanyFilter)) {
            $vesselQuery->where('company_id', $this->vesselCompanyFilter);
        }
        $totalVessels = $vesselQuery->count();

        // Dynamic count for Categories
        $categoryQuery = VesselCategory::query();
        if (!empty($this->categorySearch)) {
            $categoryQuery->where(function ($q) {
                $q->where('name', 'like', '%' . $this->categorySearch . '%')
                  ->orWhere('code', 'like', '%' . $this->categorySearch . '%');
            });
        }
        $totalCategories = $categoryQuery->count();

        return $schema
            ->components([
                Tabs::make('Vessel Settings')
                    ->label('')
                    ->tabs([
                        Tab::make('Daftar Kapal')
                            ->icon('lucide-ship')
                            // ->badge($totalVessels)
                            ->schema([
                                Actions::make([
                                    Action::make('resetFilterVessel')
                                        ->label('Reset')
                                        ->icon('heroicon-m-arrow-path')
                                        ->color('gray')
                                        ->button()
                                        ->action(function () {
                                            $this->vesselSearch = '';
                                            $this->vesselCategoryFilter = '';
                                            $this->vesselCompanyFilter = '';
                                            $this->vPage = 1;
                                            unset($this->cachedSchemas['infolist']);
                                        }),
                                    Action::make('openFilterVessel')
                                        ->label('Filter')
                                        ->icon('heroicon-m-funnel')
                                        ->color('gray')
                                        ->button()
                                        ->alpineClickHandler("\$wire.mountAction('filterVessel')"),
                                ])->alignment(Alignment::Right),
                                Grid::make(['default' => 1, 'md' => 2, 'xl' => 3])
                                    ->schema($this->getVesselSchema()),
                                Actions::make([
                                    Action::make('prevVesselPage')
                                        ->label('Sebelumnya')
                                        ->icon('heroicon-m-chevron-left')
                                        ->color('gray')
                                        ->disabled($this->vPage <= 1)
                                        ->alpineClickHandler("\$wire.previousVesselPage()"),
                                    Action::make('nextVesselPage')
                                        ->label('Selanjutnya')
                                        ->icon('heroicon-m-chevron-right')
                                        ->iconPosition('after')
                                        ->color('gray')
                                        ->disabled($this->vPage * 10 >= $totalVessels)
                                        ->alpineClickHandler("\$wire.nextVesselPage()"),
                                ])->alignment(Alignment::Center),
                            ]),
                        Tab::make('Kategori kapal')
                            ->icon('lucide-layers')
                            // ->badge($totalCategories)
                            ->schema([
                                Actions::make([
                                    Action::make('resetFilterCategory')
                                        ->label('Reset')
                                        ->icon('heroicon-m-arrow-path')
                                        ->color('gray')
                                        ->button()
                                        ->action(function () {
                                            $this->categorySearch = '';
                                            $this->cPage = 1;
                                            unset($this->cachedSchemas['infolist']);
                                        }),
                                    Action::make('openFilterCategory')
                                        ->label('Filter')
                                        ->icon('heroicon-m-funnel')
                                        ->color('gray')
                                        ->button()
                                        ->alpineClickHandler("\$wire.mountAction('filterCategory')"),
                                ])->alignment(Alignment::Right),
                                Grid::make(['default' => 1, 'md' => 2, 'xl' => 3])
                                    ->schema($this->getCategorySchema()),
                                Actions::make([
                                    Action::make('prevCategoryPage')
                                        ->label('Sebelumnya')
                                        ->icon('heroicon-m-chevron-left')
                                        ->color('gray')
                                        ->disabled($this->cPage <= 1)
                                        ->alpineClickHandler("\$wire.previousCategoryPage()"),
                                    Action::make('nextCategoryPage')
                                        ->label('Selanjutnya')
                                        ->icon('heroicon-m-chevron-right')
                                        ->iconPosition('after')
                                        ->color('gray')
                                        ->disabled($this->cPage * 10 >= $totalCategories)
                                        ->alpineClickHandler("\$wire.nextCategoryPage()"),
                                ])->alignment(Alignment::Center),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getVesselSchema(): array
    {
        $query = $this->baseVesselQuery()->with(['vesselCategory', 'company']);

        if (!empty($this->vesselSearch)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->vesselSearch . '%')
                  ->orWhere('code', 'like', '%' . $this->vesselSearch . '%');
            });
        }

        if (!empty($this->vesselCategoryFilter)) {
            $query->where('vessel_category_id', $this->vesselCategoryFilter);
        }

        if (!empty($this->vesselCompanyFilter)) {
            $query->where('company_id', $this->vesselCompanyFilter);
        }

        return $query->paginate(10, ['*'], 'vPage', $this->vPage)->map(function (Vessel $vessel) {
            return Section::make($vessel->name)
                ->icon('lucide-ship')
                // ->description($vessel->vesselCategory->name ?? 'Uncategorized')
                ->headerActions([
                    Action::make('viewVessel')
                        ->icon('heroicon-m-eye')
                        ->color('gray')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('viewVessel', { record: {$vessel->id} })"),
                    Action::make('editVessel')
                        ->icon('heroicon-m-pencil-square')
                        ->color('gray')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('editVessel', { record: {$vessel->id} })"),
                    Action::make('deleteVessel')
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('deleteVessel', { record: {$vessel->id} })"),
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
                    ])->columns(2)->gap(),
                ])
                ->columnSpan(1);
        })->toArray();
    }

    protected function getCategorySchema(): array
    {
        $query = VesselCategory::withCount('vessels');

        if (!empty($this->categorySearch)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->categorySearch . '%')
                  ->orWhere('code', 'like', '%' . $this->categorySearch . '%');
            });
        }

        return $query->paginate(10, ['*'], 'cPage', $this->cPage)->map(function (VesselCategory $category) {
            return Section::make($category->name)
                ->icon('lucide-ship')
                ->headerActions([
                    Action::make('viewCategory')
                        ->icon('heroicon-m-eye')
                        ->color('gray')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('viewCategory', { record: {$category->id} })"),
                    Action::make('editCategory')
                        ->icon('heroicon-m-pencil-square')
                        ->color('gray')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('editCategory', { record: {$category->id} })"),
                    Action::make('deleteCategory')
                        ->icon('lucide-trash-2')
                        ->color('danger')
                        ->iconButton()
                        ->alpineClickHandler("\$wire.mountAction('deleteCategory', { record: {$category->id} })"),
                ])
                ->schema([
                    Group::make([
                        Text::make('Kode: ' . strtoupper($category->code ?: '-'))
                            ->color('gray')
                            ->size('xs'),
                        Text::make($category->vessels_count . ' Kapal Terdaftar')
                            ->badge()
                            ->color('warning'),
                        Text::make(\Illuminate\Support\Str::limit($category->description, 50) ?: '-')
                            ->color('gray')
                            ->size('xs'),
                    ])->columns(2)->gap(),
                ])->columnSpan(1);
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
                ->action(function (array $data) {
                    Vessel::create($data);
                    unset($this->cachedSchemas['infolist']);
                }),

            Action::make('createCategory')
                ->label('Tambah Kategori')
                ->icon('heroicon-m-plus')
                ->color('warning')
                ->form([
                    TextInput::make('name')->required(),
                    TextInput::make('code'),
                    Textarea::make('description'),
                ])
                ->action(function (array $data) {
                    VesselCategory::create($data);
                    unset($this->cachedSchemas['infolist']);
                }),
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
                Textarea::make('description'),
            ])
            ->fillForm(function (array $arguments) {
                $record = Vessel::find($arguments['record'] ?? null);
                return $record ? $record->toArray() : [];
            })
            ->action(function (array $arguments, array $data) {
                $record = Vessel::find($arguments['record'] ?? null);
                if ($record) {
                    $record->update($data);
                }
                unset($this->cachedSchemas['infolist']);
            });
    }

    public function deleteVesselAction(): Action
    {
        return Action::make('deleteVessel')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $record = Vessel::find($arguments['record'] ?? null);
                if ($record) {
                    $record->delete();
                }
                unset($this->cachedSchemas['infolist']);
            });
    }

    public function editCategoryAction(): Action
    {
        return Action::make('editCategory')
            ->form([
                TextInput::make('name')->required(),
                TextInput::make('code'),
                Textarea::make('description'),
            ])
            ->fillForm(function (array $arguments) {
                $record = VesselCategory::find($arguments['record'] ?? null);
                return $record ? $record->toArray() : [];
            })
            ->action(function (array $arguments, array $data) {
                $record = VesselCategory::find($arguments['record'] ?? null);
                if ($record) {
                    $record->update($data);
                }
                unset($this->cachedSchemas['infolist']);
            });
    }

    public function deleteCategoryAction(): Action
    {
        return Action::make('deleteCategory')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $record = VesselCategory::find($arguments['record'] ?? null);
                if ($record) {
                    $record->delete();
                }
                unset($this->cachedSchemas['infolist']);
            });
    }

    public function viewVesselAction(): Action
    {
        return Action::make('viewVessel')
            ->modalHeading('Detail Kapal')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->form([
                TextInput::make('name')->disabled(),
                TextInput::make('code')->disabled(),
                Select::make('vessel_category_id')
                    ->options(VesselCategory::pluck('name', 'id'))
                    ->disabled(),
                Select::make('company_id')
                    ->options(Company::pluck('name', 'id'))
                    ->disabled(),
                TextInput::make('flag')->disabled(),
                Textarea::make('description')->disabled(),
            ])
            ->fillForm(function (array $arguments) {
                $record = Vessel::find($arguments['record'] ?? null);
                return $record ? $record->toArray() : [];
            });
    }

    public function viewCategoryAction(): Action
    {
        return Action::make('viewCategory')
            ->modalHeading('Detail Kategori')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->form([
                TextInput::make('name')->disabled(),
                TextInput::make('code')->disabled(),
                Textarea::make('description')->disabled(),
            ])
            ->fillForm(function (array $arguments) {
                $record = VesselCategory::find($arguments['record'] ?? null);
                return $record ? $record->toArray() : [];
            });
    }

    public function filterVesselAction(): Action
    {
        return Action::make('filterVessel')
            ->modalHeading('Filter Kapal')
            ->modalSubmitActionLabel('Terapkan')
            ->form([
                TextInput::make('search')
                    ->label('Cari Nama / Kode')
                    ->placeholder('Masukkan nama atau kode kapal...'),
                Select::make('vessel_category_id')
                    ->label('Kategori')
                    ->options(VesselCategory::pluck('name', 'id'))
                    ->placeholder('Semua Kategori'),
                Select::make('company_id')
                    ->label('Perusahaan')
                    ->options(Company::pluck('name', 'id'))
                    ->placeholder('Semua Perusahaan'),
            ])
            ->fillForm([
                'search' => $this->vesselSearch,
                'vessel_category_id' => $this->vesselCategoryFilter,
                'company_id' => $this->vesselCompanyFilter,
            ])
            ->action(function (array $data) {
                $this->vesselSearch = $data['search'] ?? '';
                $this->vesselCategoryFilter = $data['vessel_category_id'] ?? '';
                $this->vesselCompanyFilter = $data['company_id'] ?? '';
                $this->vPage = 1; // Reset ke halaman awal
                unset($this->cachedSchemas['infolist']);
            });
    }

    public function filterCategoryAction(): Action
    {
        return Action::make('filterCategory')
            ->modalHeading('Filter Kategori')
            ->modalSubmitActionLabel('Terapkan')
            ->form([
                TextInput::make('search')
                    ->label('Cari Nama / Kode')
                    ->placeholder('Masukkan nama atau kode kategori...'),
            ])
            ->fillForm([
                'search' => $this->categorySearch,
            ])
            ->action(function (array $data) {
                $this->categorySearch = $data['search'] ?? '';
                $this->cPage = 1; // Reset ke halaman awal
                unset($this->cachedSchemas['infolist']);
            });
    }
}
