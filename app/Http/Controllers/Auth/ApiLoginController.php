<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiLoginController
{
    public function __invoke(Request $request)
    {
        $username = $request->input('username');
        Log::warning("Try log ".$username);
        $guard = Filament::auth();
        if ($guard->check()) {
            return response()->json(['status' => 'success', 'message' => 'Already Authenticated']);
        }
        try {
            $user = User::query()->where('username', $username)->first();
            if (!$user instanceof FilamentUser) {

                Log::warning("user not found ".$username);

                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 401);
            }

            $guard->login($user, true);

            Log::warning("user success ".$user->first_name);
            Log::warning("user id ".$guard->id());

            return response()->json(['status' => 'success', 'message' => 'Authenticated'.$user->first_name]);
        } catch (Exception $e) {
            Log::warning("user fail ".$username." ".$e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Failed to authenticate token'], 500);
        }
    }

}
