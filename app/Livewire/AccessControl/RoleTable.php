<?php

namespace App\Livewire\AccessControl;

use App\Models\Role;
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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class RoleTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Peran (Roles)')
            ->description('Gunakan halaman ini untuk menambah, mengubah, atau menghapus peran yang tersedia untuk pengaturan keamanan.')
            ->query(Role::query()->where('name', '!=', 'super_admin'))
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Peran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Slug / Kode')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Keterangan')
                    ->limit(50),
            ])
            ->actions([
                EditAction::make()->disabled()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
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
                DeleteAction::make()->disabled(),
            ])
            ->headerActions([
                CreateAction::make()->disabled()
                    ->label('Tambah Peran')
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->required()
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
