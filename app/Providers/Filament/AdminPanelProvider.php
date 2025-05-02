<?php

namespace App\Providers\Filament;

use App\Constant\DepartmentEnum;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\Pages\InternalShutter;
use App\Filament\Widgets\ActionsWidget;
use App\Http\Middleware\FilamentPanelColorMiddleware;
use App\Repository\UserRepository;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->path('admin')
            ->colors([
                'primary' => Color::Slate,
                'secondary' => Color::Pink,
            ])
            ->databaseNotifications()
            ->unsavedChangesAlerts()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                InternalShutter::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //  Widgets\AccountWidget::class,
                ActionsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                FilamentPanelColorMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigationItems([
                NavigationItem::make('internal')
                    ->icon('tabler-arrow-badge-left')
                    ->label('Volet interne')
                    ->url('/admin/internal'),
                //->url(StrategicObjectiveResource::getUrl('internal',panel: 'admin')),
            ])
            ->userMenuItems([
                MenuItem::make()
                    ->label('Ville')
                    ->url(fn() => route('select.department', ['department' => DepartmentEnum::VILLE->value]))
                    ->icon('tabler-switch')
                    ->visible(fn() => count(auth()->user()->departments) > 1),
                MenuItem::make()
                    ->label('Cpas')
                    ->url(fn() => route('select.department', ['department' => DepartmentEnum::CPAS->value]))
                    ->icon('tabler-switch')
                    ->visible(fn() => count(auth()->user()->departments) > 1),
            ]);
    }
}

FilamentView::registerRenderHook(
    PanelsRenderHook::SIDEBAR_NAV_START,
    function (): View {
        return view('filament.sidebar', ['department' => UserRepository::departmentSelected()]);
    }
);
