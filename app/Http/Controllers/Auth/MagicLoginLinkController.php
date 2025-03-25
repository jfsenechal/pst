<?php

namespace App\Http\Controllers\Auth;

use App\Auth\MagicLoginLink;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
use App\Models\User;
use Auth;

class MagicLoginLinkController extends Controller
{
    public function create(Request $request, User $user)
    {
        $user->notify(new MagicLoginLink(Password::createToken($user)));

        return response()->json(['message' => 'Magic login link sent.']);
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        if (!Password::tokenExists($user, $request->token)) {
            abort(404);
        }

        Auth::login($user);

        Password::deleteToken($user);

        return redirect()->intended('dashboard');
    }
}
