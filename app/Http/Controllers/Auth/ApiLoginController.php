<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Auth;
use Exception;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Request;

class ApiLoginController
{
    public function __invoke(Request $request)
    {
        $credentials = ([
            'mail' => $request->email,
            'password' => $request->password,
        ]);

        if (Auth::check()) {
            return response()->json(['status' => 'success', 'message' => 'Already Authenticated']);
        }
        try {
            //$user = User::query()->where('id', '=', $request->bearerToken())->firstOrFail();
            $user = User::query()->where('username', config('app.pst.user_login_test'))->first();
            if (!$user instanceof FilamentUser) {
                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 401);
            }

            Filament::auth()->login($user, true);

            return response()->json(['status' => 'success', 'message' => 'Authenticated'.$user->name]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to authenticate token'], 500);
        }
    }

}
