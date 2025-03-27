<?php

namespace App\Http\Middleware;

use App\Repository\FilamentColor;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentPanelColorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();
        $colors = FilamentColor::userColor();
        $panel->colors($colors);
        return $next($request);
    }
}
