<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict();
        if (!app()->environment('production')) {
            Mail::alwaysTo('jf@marche.be');
            // Add following lines to force laravel to use APP_URL as root url for the app.
            //  $strBaseURL = $this->app['url'];
            //  $strBaseURL->useOrigin(config('app.url'));
        }
        /*    FilamentView::registerRenderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn(): string => Blade::render('@livewire(Filament\Livewire\GlobalSearch::class, [\'lazy\' => true])'),
            );*/
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            fn(): View => view('filament.login_form'),
        );
    }
}
