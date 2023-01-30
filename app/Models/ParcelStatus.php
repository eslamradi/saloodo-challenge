<?php

namespace App\Models;

use ReflectionClass;

class ParcelStatus
{
    public const PENDING = 1;
    public const BEING_PICKED_UP = 2;
    public const BEING_DELIVERED = 3;
    public const DELIVERED = 4;

    public static function wording($status)
    {
        $reflector = new ReflectionClass(self::class);
        $statuses =  $reflector->getConstants();
        foreach ($statuses as $key => $one) {
            if ($one == $status) {
                return $key;
            }
        }
        return null;
    }
}
