<?php

namespace App\Http\Controllers\Auth;

use App\Ldap\User as UserLdap;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Support\Facades\Request;
use LdapRecord\Container;

class LoginController
{
    public function __invoke(Request $request)
    {


        dd('no');


        if (Auth::check()) {
            return response()->json(['status' => 'success', 'message' => 'Already Authenticated']);
        }
        try {
            //$user = User::query()->where('id', '=', $request->bearerToken())->firstOrFail();
            $user = User::query()->find(1);
            if (!$user) {
                return response()->json(['status' => 'error', 'message' => 'Invalid token'], 401);
            }
            Auth::login($user);

            return response()->json(['status' => 'success', 'message' => 'Authenticated'.$user->name]);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to authenticate token'], 500);
        }
    }

}
