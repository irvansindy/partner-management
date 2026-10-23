<?php

namespace App\Support;

class CountryCatalog
{
    public static function all(): array
    {
        return config('countries', []);
    }

    public static function find(string $iso): ?array
    {
        $iso = strtoupper($iso);

        foreach (self::all() as $country) {
            if ($country['code'] === $iso) {
                return $country;
            }
        }

        return null;
    }
}
