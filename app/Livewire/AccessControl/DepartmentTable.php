<?php

namespace App\Livewire\AccessControl;

use App\Models\Department;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class DepartmentTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Departemen')
            ->description('Gunakan halaman ini untuk menambah, mengubah, atau menghapus departemen yang tersedia dalam sistem.')
            ->query(Department::query())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Departemen')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->badge(),
                TextColumn::make('location')
                    ->label('Lokasi'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('location')
                            ->maxLength(255),
                        Select::make('status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Non-aktif',
                            ])
                            ->required(),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Departemen')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('code')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('location')
                            ->maxLength(255),
                        Select::make('status')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Non-aktif',
                            ])
                            ->default('active')
                            ->required(),
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}
