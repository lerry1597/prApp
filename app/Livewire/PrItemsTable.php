<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\PrHeader;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class PrItemsTable extends Component implements HasTable, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public ?PrHeader $record = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Barang')
            ->query(fn(): Builder => $this->getItemsQuery())
            ->columns([
                TextColumn::make('row_number')
                    ->label('No.')
                    ->rowIndex()
                    ->width('50px'),

                TextColumn::make('itemCategory.name')
                    ->label('Kategori')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Jenis / Nama Barang')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('size')
                    ->label('Ukuran / Spesifikasi')
                    ->placeholder('-'),

                TextInputColumn::make('quantity')
                    ->label('Jumlah')
                    ->type('number')
                    ->inputMode('decimal')
                    ->step(1)
                    ->rules(['required', 'numeric', 'min:1'])
                    ->alignRight(),

                TextColumn::make('unit')
                    ->label('Satuan')
                    ->placeholder('-'),

                TextColumn::make('remaining')
                    ->label('Sisa')
                    ->numeric()
                    ->alignRight()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('item_category_id')
                    ->label('Kategori')
                    ->relationship('itemCategory', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('unit')
                    ->label('Satuan')
                    ->options(
                        fn() => Item::query()
                            ->when(
                                filled($this->record?->detail?->id),
                                fn(Builder $query) => $query->where('pr_detail_id', $this->record?->detail?->id),
                                fn(Builder $query) => $query->whereNull('id')
                            )
                            ->whereNotNull('unit')
                            ->where('unit', '!=', '')
                            ->distinct()
                            ->orderBy('unit')
                            ->pluck('unit', 'unit')
                            ->toArray()
                    )
                    ->searchable(),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->striped();
    }

    protected function getItemsQuery(): Builder
    {
        $prDetailId = $this->record?->detail?->id;

        return Item::query()
            ->when(
                filled($prDetailId),
                fn(Builder $query) => $query->where('pr_detail_id', $prDetailId),
                fn(Builder $query) => $query->whereNull('id')
            )
            ->with('itemCategory')
            ->orderBy('id');
    }

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pr-items-table');
    }
}
