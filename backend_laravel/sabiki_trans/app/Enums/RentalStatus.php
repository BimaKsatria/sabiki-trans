<?php

namespace App\Enums;

enum RentalStatus: string
{
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Overdue = 'overdue';

    public static function options(): array
    {
        return [
            self::Ongoing->value => 'ongoing',
            self::Completed->value => 'completed',
            self::Overdue->value => 'overdue',
        ];
    }
}
