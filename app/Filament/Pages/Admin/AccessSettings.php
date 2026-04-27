<?php

namespace App\Filament\Pages\Admin;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class AccessSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $slug = 'access-settings';

    protected string $view = 'filament.pages.access-settings';

    protected static ?string $navigationLabel = 'Akses & Organisasi';

    protected static ?string $title = 'Pengaturan Akses & Organisasi';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 100;
}
