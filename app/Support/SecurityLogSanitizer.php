<?php

namespace App\Support;

use Illuminate\Http\Request;
use Throwable;

class SecurityLogSanitizer
{
    public function requestMetadata(Request $request): array
    {
        $userAgent = preg_replace('/[^\x20-\x7E]/', '', (string) $request->userAgent());

        return [
            'method' => $request->method(),
            'route' => optional($request->route())->getName(),
            'status' => $request->attributes->get('response_status'),
            'request_id' => $request->attributes->get('request_id'),
            'ip_hash' => $this->hashIp($request->ip()),
            'user_agent' => mb_substr($userAgent, 0, 255),
        ];
    }

    public function exceptionContext(Throwable $error, Request $request): array
    {
        return array_merge($this->requestMetadata($request), [
            'exception' => get_class($error),
        ]);
    }

    private function hashIp(?string $ip): ?string
    {
        if (!$ip) {
            return null;
        }

        return hash_hmac('sha256', $ip, (string) config('app.key'));
    }
}
