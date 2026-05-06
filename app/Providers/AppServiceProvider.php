<?php

namespace App\Providers;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use DragonCode\Support\Helpers\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Jika URL mengandung 'ngrok-free.app' atau 'https', paksa ke HTTPS
        if (request()->header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => \Illuminate\Support\Facades\Blade::render('<x-app.location-guard />'),
        );
    }
}
