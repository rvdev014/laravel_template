<?php

namespace App\Enums;

enum Gender: int
{
    case MALE = 1;
    case FEMALE = 2;

    public static function valuesStr(): string
    {
        return implode(',', self::values());
    }

    private static function values(): array
    {
        return [
            self::MALE->value,
            self::FEMALE->value,
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female'
        };
    }
}
