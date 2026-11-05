<?php

namespace App\Audit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditContext
{
    public function values(): array
    {
        $request = app()->bound('request') ? app(Request::class) : null;
        $userId = Auth::id();

        return [
            'request_id' => $request?->attributes->get('request_id'),
            'user_id' => $userId,
            'actor_type' => $userId ? 'user' : ($request ? 'public' : 'system'),
            'route' => $request ? optional($request->route())->getName() : null,
            'method' => $request?->method(),
            'ip_hash' => $request ? $this->hashIp($request->ip()) : null,
            'user_agent' => $request ? $this->sanitizeUserAgent($request->userAgent()) : null,
        ];
    }

    private function hashIp(?string $ip): ?string
    {
        return $ip ? hash_hmac('sha256', $ip, (string) config('app.key')) : null;
    }

    private function sanitizeUserAgent(?string $userAgent): string
    {
        $value = preg_replace('/[^\x20-\x7E]/', '', (string) $userAgent);

        return mb_substr($value, 0, 255);
    }
}
