<?php

namespace App\Livewire\AccessControl;

use App\Models\PermissionMappingLog;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Livewire\Component;

class AuditMappingTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Riwayat Pemetaan Izin')
            ->description('Catatan perubahan pada hak akses yang diberikan kepada setiap peran.')
            ->query(PermissionMappingLog::query()->latest('changed_at'))
            ->columns([
                TextColumn::make('changed_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('changer.name')
                    ->label('Oleh'),
                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'grant' => 'success',
                        'revoke' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('role.title')
                    ->label('Peran'),
                TextColumn::make('permission.title')
                    ->label('Izin'),
                TextColumn::make('details')
                    ->label('Detail')
                    ->limit(30),
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
