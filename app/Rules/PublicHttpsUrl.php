<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PublicHttpsUrl implements Rule
{
    public static function normalize(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return preg_match('#^[a-z][a-z0-9+.-]*://#i', $value) ? $value : 'https://'.$value;
    }

    public function passes($attribute, $value): bool
    {
        $value = self::normalize($value);

        if ($value === null || preg_match('/\s/u', $value) || !filter_var($value, FILTER_VALIDATE_URL)) {
            return false;
        }

        $parts = parse_url($value);
        $host = $parts['host'] ?? '';

        return ($parts['scheme'] ?? '') === 'https'
            && !isset($parts['user'])
            && !isset($parts['pass'])
            && $host === strtolower($host)
            && preg_match('/^[a-z0-9.-]+$/', $host) === 1
            && strpos($host, '.') !== false
            && filter_var($host, FILTER_VALIDATE_IP) === false
            && preg_match('/^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/', $host) === 1;
    }

    public function message(): string
    {
        return 'Alamat website harus menggunakan HTTPS dan domain publik yang valid. | Website must use HTTPS and a valid public domain.';
    }
}
