<?php

use App\Http\Controllers\Auth\LoginLinkController;
use App\Http\Controllers\PdfExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/admin'));
Route::get('/loginfromintranet/{uuid}', LoginLinkController::class);
Route::get('/download-action/{action}', [PdfExportController::class, 'download'])->name('download.action');
