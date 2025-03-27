<?php

namespace App\Providers;

use App\Repository\FilamentColor;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class SetColorProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $panel = Filament::getPanel('admin');
        $colors = FilamentColor::userColor();
        $panel->colors($colors);
    }
}
