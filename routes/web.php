<?php

use App\Http\Controllers\Auth\LoginLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/admin'));
Route::get('/loginfromintranet/{uuid}', LoginLinkController::class);
