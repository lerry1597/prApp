<?php

namespace App\Filament\Resources\Admin\Companies\Pages;

use App\Filament\Resources\Admin\Companies\CompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getMaxContentWidth(): string|\Filament\Support\Enums\Width|null
    {
        return 'full';
    }
}
