<?php

namespace App\Enums;

enum CarStatus: string
{
    case Available = 'available';
    case Rented = 'rented';
    case Maintenance = 'maintenance';

    public static function options(): array
    {
        return [
            self::Available->value => 'Available',
            self::Rented->value => 'Rented',
            self::Maintenance->value => 'Maintenance',
        ];
    }
}
