<?php

namespace App\Enums;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Cancelled = 'cancelled';

    public static function options(): array
    {
        return [
            self::Pending->value => 'pending',
            self::Approved->value => 'approved',
            self::Cancelled->value => 'cancelled',
        ];
    }
}
