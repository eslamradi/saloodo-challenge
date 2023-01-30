<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class Role
{
    /**
     * if you have guards foreach role, make sure they are following the same name convention
     * e.g: role => customer , guard => customer
     */

    public const CUSTOMER = 'customer';
    public const BIKER = 'biker';

    /**
     * return an array of available roles
     *
     * @return array
     */
    public static function all()
    {
        $reflector = new ReflectionClass(self::class);
        return $reflector->getConstants();
    }
}
