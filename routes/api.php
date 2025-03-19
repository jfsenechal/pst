<?php

use App\Http\Controllers\Auth\ApiLoginController;
use Illuminate\Support\Facades\Route;

Route::post('/jfs', ApiLoginController::class);
