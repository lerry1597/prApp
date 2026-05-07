<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
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
    /** @var list<string> */
    protected array $panelStylesheets = [
        'css/components/date-picker.css',
        'css/approved-pr-list.css',
        'css/manage-pr-headers.css',
        'css/pr-flow-history.css',
        'css/processed-po-list.css',
        'css/procurement-officer-pr-list.css',
        'css/purchase-requisition-form.css',
        'css/purchase-requisition-history.css',
        'css/purchase-requisition-request-list.css',
        'css/purchase-requisition.css',
        'css/vessel-pr-overview.css',
    ];

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
                \App\Filament\Pages\App\PurchaseRequisitionRequestList::class,
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
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => $this->renderPanelStylesheets(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): string => $this->renderNavigationLoaderMarkup(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => $this->renderNavigationLoaderScript(),
            );
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton(
            \Filament\Auth\Http\Responses\Contracts\LoginResponse::class,
            \App\Http\Responses\CustomLoginResponse::class
        );
    }

    protected function renderPanelStylesheets(): string
    {
        return collect($this->panelStylesheets)
            ->map(function (string $path): string {
                $href = asset($path);

                return sprintf(
                    '<link rel="preload" as="style" href="%1$s"><link rel="stylesheet" href="%1$s">',
                    e($href),
                );
            })
            ->implode('');
    }

    protected function renderNavigationLoaderMarkup(): string
    {
        return <<<'HTML'
<style>
    #app-panel-nav-loader {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(248, 250, 252, 0.72);
        backdrop-filter: blur(6px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.16s ease, visibility 0.16s ease;
        z-index: 99999;
    }

    #app-panel-nav-loader::before {
        content: '';
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 999px;
        border: 3px solid rgba(37, 99, 235, 0.18);
        border-top-color: #2563eb;
        animation: app-panel-nav-spin 0.75s linear infinite;
    }

    html.app-nav-loading #app-panel-nav-loader {
        opacity: 1;
        visibility: visible;
    }

    .dark #app-panel-nav-loader {
        background: rgba(15, 23, 42, 0.72);
    }

    .dark #app-panel-nav-loader::before {
        border-color: rgba(96, 165, 250, 0.22);
        border-top-color: #60a5fa;
    }

    @keyframes app-panel-nav-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<div id="app-panel-nav-loader" aria-hidden="true"></div>
HTML;
    }

    protected function renderNavigationLoaderScript(): string
    {
        return <<<'HTML'
<script>
    (() => {
        let navLoaderTimer = null;

        const showLoader = () => {
            clearTimeout(navLoaderTimer);
            navLoaderTimer = window.setTimeout(() => {
                document.documentElement.classList.add('app-nav-loading');
            }, 80);
        };

        const hideLoader = () => {
            clearTimeout(navLoaderTimer);
            document.documentElement.classList.remove('app-nav-loading');
        };

        document.addEventListener('livewire:navigating', showLoader);
        document.addEventListener('livewire:navigated', () => {
            window.requestAnimationFrame(() => hideLoader());
        });
        window.addEventListener('load', hideLoader, { once: true });
    })();
</script>
HTML;
    }
}
