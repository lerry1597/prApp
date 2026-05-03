<?php

namespace App\Filament\Resources\App\PrHeaderResource\Pages;

use App\Filament\Resources\App\PrHeaderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePrHeaders extends ManageRecords
{
    protected static string $resource = PrHeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol buat dihapus karena Approver tidak membuat PR
        ];
    }
}
