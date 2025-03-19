<?php

namespace App\Auth;

use App\Ldap\User as UserLdap;
use App\Models\User;
use LdapRecord\Container;

class LdapAuthService
{
    public static function checkPassword(string $username, string $password): ?User
    {
        $user = User::where('username', '=', $username)->first();
        if ($user) {
            $userLdap = UserLdap::where('sAMAccountName', '=', $user->username)->first();
            if (!$userLdap) {
                return null;
            }
            $connection = Container::getConnection('default');

            if ($connection->auth()->attempt($userLdap->getDn(), $password)) {
                return $user;
            } else {
                $message = $connection->getLdapConnection()->getDiagnosticMessage();

                if (strpos($message, '532') !== false) {
                    //"Your password has expired.";
                    return null;
                }
            }
        }

        return null;
    }
}
