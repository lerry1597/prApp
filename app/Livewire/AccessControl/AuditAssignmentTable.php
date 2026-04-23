<?php

namespace App\Livewire\AccessControl;

use App\Models\RoleAssignmentLog;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Livewire\Component;

class AuditAssignmentTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithForms;
    use InteractsWithTable;
    use InteractsWithActions;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Riwayat Penugasan Peran')
            ->description('Catatan perubahan pada hak akses yang diberikan kepada setiap peran.')
            ->query(RoleAssignmentLog::query()->latest('assigned_at'))
            ->columns([
                TextColumn::make('assigned_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('assigner.name')
                    ->label('Oleh'),
                TextColumn::make('action')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'assign' => 'success',
                        'revoke' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')
                    ->label('Target Pengguna'),
                TextColumn::make('role.title')
                    ->label('Peran'),
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
