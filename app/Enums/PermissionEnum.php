<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case USER_CREATE = 'user_create';
    case USER_READ = 'user_read';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    public static function values(): array
    {
        return [
            self::USER_CREATE,
            self::USER_READ,
            self::USER_UPDATE,
            self::USER_DELETE,
        ];
    }

    public static function valueNames(): array
    {
        return array_map(fn($value) => $value->value, self::values());
    }
}
