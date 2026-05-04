<?php

namespace App\Filament\Pages\App;

use App\Constants\RoleConstant;
use Filament\Pages\Dashboard;

class AppDashboard extends Dashboard
{
    protected static ?string $slug = 'dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Dashboard disembunyikan khusus role requester kru kapal.
        return ! $user->roles()->where('name', RoleConstant::VESSEL_CREW_REQUESTER)->exists();
    }
}
