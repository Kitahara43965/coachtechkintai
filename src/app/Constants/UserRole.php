<?php

namespace App\Constants;

class UserRole
{
    public const UNDEFINED = null;
    public const USER  = 'user';
    public const ADMIN = 'admin';

    public static function toArray()
    {
        return [
            'UNDEFINED'=>self::UNDEFINED,
            'USER'=> self::USER,
            'ADMIN' => self::ADMIN,
        ];
    }
}