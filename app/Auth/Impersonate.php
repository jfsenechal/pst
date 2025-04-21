<?php

namespace App\Auth;

use Filament\Facades\Filament;

//https://www.youtube.com/watch?v=E6qgAizaMEw
//https://www.youtube.com/watch?v=E6qgAizaMEw
//https://github.com/404labfr/laravel-impersonate
class Impersonate
{
    protected function clearAuthHashes(): void
    {
        session()->forget(array_unique([
            'password_hash_'.session('impersonate.guard'),
            'password_hash_'.Filament::getCurrentPanel()->getAuthGuard(),
            'password_hash_'.Filament::getPanel(session()->get('impersonate.back_to_panel'))->getAuthGuard(),
            'password_hash_'.auth()->getDefaultDriver(),
            'password_hash_sanctum',
        ]));
    }

}
