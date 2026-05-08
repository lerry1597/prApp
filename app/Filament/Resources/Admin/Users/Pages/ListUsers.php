<?php

namespace App\Filament\Resources\Admin\Users\Pages;

use App\Filament\Resources\Admin\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('create-crew')
                    ->label('Kru Kapal')
                    ->icon('lucide-ship')
                    ->url(UserResource::getUrl('create-crew')),

                Action::make('create-general')
                    ->label('Pengguna Umum')
                    ->icon('heroicon-o-user')
                    ->url(UserResource::getUrl('create-general')),
            ])
                ->label('Tambahkan Pengguna Baru')
                ->icon('heroicon-o-user-plus')
                ->button()
                ->color('primary'),
        ];
    }
}
