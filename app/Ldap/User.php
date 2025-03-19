<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class User extends Model
{
    /**
     * The object classes of the LDAP model.
     */
    public static array $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];

    //public   $filter1 = "(&(|(sAMAccountName=$uid))(objectClass=person))";
    //public   $filter = '(&(objectClass=person)(!(uid=acmarche)))';
}
