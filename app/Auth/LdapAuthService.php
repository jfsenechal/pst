<?php

namespace App\Auth;

use App\Ldap\User as UserLdap;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use LdapRecord\Container;

class LdapAuthService
{
    public static function checkPassword(string $username, string $password): ?User
    {
        $user = User::where('username', '=', $username)->first();
        if (app()->environment('local')) {
            Log::warning("local ".$username);

            return $user;
        }
        if ($user) {
            Log::warning("user found ".$username);
            $userLdap = UserLdap::where('sAMAccountName', '=', $user->username)->first();
            if (!$userLdap) {
                Log::warning("user ldap not found ".$username);

                return null;
            }
            $connection = Container::getConnection('default');

            if ($connection->auth()->attempt($userLdap->getDn(), $password)) {
                return $user;
            } else {
                $message = $connection->getLdapConnection()->getDiagnosticMessage();

                Log::warning("failed log ".$message);
                if (strpos($message, '532') !== false) {
                    //"Your password has expired.";
                    return null;
                }
            }
        }

        return null;
    }
}
