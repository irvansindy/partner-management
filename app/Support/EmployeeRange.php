<?php

namespace App\Support;

final class EmployeeRange
{
    public static function values(): array
    {
        return [
            '1-100',
            '101-500',
            '501-1000',
            '1001-5000',
            '5001-10000',
            '10000+',
        ];
    }
}
