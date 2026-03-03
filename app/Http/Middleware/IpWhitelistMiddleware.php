<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiWhitelist;
use App\Helpers\FormatResponseJson;
use Symfony\Component\HttpFoundation\Response;
class IpWhitelistMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strpos($request->userAgent(), 'Scramble') !== false || $request->is('docs/*')) {
            return $next($request);
        }

        $ip = $request->ip();
        $allowed = ApiWhitelist::where('ip_address', $ip)->exists();

        if (! $allowed) {
            return FormatResponseJson::error(
                'IP address not whitelisted',
                'Access denied',
                403
            );
        }

        return $next($request);
    }
}
