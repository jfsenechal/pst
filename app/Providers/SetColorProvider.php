<?php

namespace App\Providers;

use App\Http\Middleware\SetFilamentColor;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class SetColorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $panel = Filament::getPanel('admin');
        $panel->colors(fn() => SetFilamentColor::userColor());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
       // dump(Auth::user());
    }
}
