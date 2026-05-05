<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

use App\Filament\Pages\Auth\CustomLogin;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->spa()
            ->homeUrl(function (): string {
                $user = auth()->user();

                if ($user?->roles()->where('name', \App\Constants\RoleConstant::VESSEL_CREW_REQUESTER)->exists()) {
                    return route('filament.app.pages.purchase-requisition-form');
                }

                if ($user?->roles()->where('name', \App\Constants\RoleConstant::PROCUREMENT_OFFICER)->exists()) {
                    return route('filament.app.pages.procurement-officer-pr-list');
                }

                return route('filament.app.pages.dashboard');
            })
            ->topNavigation(true)
            ->discoverResources(in: app_path('Filament/Resources/App'), for: 'App\Filament\Resources\App')
            ->discoverPages(in: app_path('Filament/Pages/App'), for: 'App\Filament\Pages\App')
            ->resources([
                \App\Filament\Resources\App\PrHeaderResource::class,
            ])
            ->pages([
                \App\Filament\Pages\App\AppDashboard::class,
                \App\Filament\Pages\App\PurchaseRequisitionForm::class,
                \App\Filament\Pages\App\PurchaseRequisition::class,
                \App\Filament\Pages\App\PurchaseRequisitionHistory::class,
                \App\Filament\Pages\App\ProcurementOfficerPrList::class,
                \App\Filament\Pages\App\ProcessedPoList::class,
                \App\Filament\Pages\App\VesselPrOverview::class,
                \App\Filament\Pages\App\PrFlowHistory::class,
                \App\Filament\Pages\App\ApprovedPrList::class,
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\Filament\App\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                \App\Http\Middleware\SetLocale::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\CustomLoginResponse::class
        );
    }
}
