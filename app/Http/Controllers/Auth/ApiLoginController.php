<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Auth;
use Exception;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ApiLoginController
{
    public function __invoke(Request $request)
    {
        $username = $request->username;
        Log::warning("Try log ".$username);

        if (Auth::check()) {
            return response()->json(['status' => 'success', 'message' => 'Already Authenticated']);
        }
        try {
            //$user = User::query()->where('id', '=', $request->bearerToken())->firstOrFail();

            $user = User::query()->where('username', $username)->first();
            if (!$user instanceof FilamentUser) {

                Log::warning("user not found ".$username);

                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 401);
            }

            Filament::auth()->login($user, true);

            Log::warning("user success ".$username);

            return response()->json(['status' => 'success', 'message' => 'Authenticated'.$user->name]);
        } catch (Exception $e) {
            Log::warning("user fail ".$username." ".$e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Failed to authenticate token'], 500);
        }
    }

}
