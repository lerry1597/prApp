<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Admin\AdminLogin;
use App\Filament\Widgets\Admin\DashboardOverview;
use App\Filament\Widgets\Admin\PrRequestByVessel;
use App\Filament\Widgets\Admin\PurchaseRequestChart;
use App\Http\Middleware\SetLocale;
use CraftForge\FilamentLanguageSwitcher\FilamentLanguageSwitcherPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(AdminLogin::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugins([
                FilamentLanguageSwitcherPlugin::make()
                    ->locales(['id', 'en'])
                    ->showOnAuthPages(),
            ])
            ->discoverResources(in: app_path('Filament/Resources/Admin'), for: 'App\Filament\Resources\Admin')
            ->discoverPages(in: app_path('Filament/Pages/Admin'), for: 'App\Filament\Pages\Admin')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets/Admin'), for: 'App\Filament\Widgets\Admin')
            ->widgets([
                DashboardOverview::class,
                PurchaseRequestChart::class,
                PrRequestByVessel::class,
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make()
                     ->label('Pengaturan')
                     ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->spa()
            ->topNavigation(true)
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_START,
                fn (): string => '<style>::-webkit-scrollbar{display:none !important;width:0 !important;background:transparent !important;}*{-ms-overflow-style:none !important;scrollbar-width:none !important;}</style>',
            );
    }
}
