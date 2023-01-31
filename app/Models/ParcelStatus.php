<?php

namespace App\Models;

use ReflectionClass;

class ParcelStatus
{
    public const PENDING = 1;
    public const RESERVED = 2;
    public const PICKED_UP = 3;
    public const BEING_DELIVERED = 4;
    public const DELIVERED = 5;

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

    public static function getBusyStatuses()
    {
        return [
            self::RESERVED,
            self::PICKED_UP,
            self::BEING_DELIVERED
        ];
    }
}
