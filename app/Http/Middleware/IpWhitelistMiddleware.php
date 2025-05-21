<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ApiWhitelist;
use App\Helpers\FormatResponseJson;

class IpWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        // dd($ip);
        $allowed = ApiWhitelist::where('ip_address', $ip)->exists();

        if (! $allowed) {
            // return response()->json(['message' => 'Access denied'], 403);
            return FormatResponseJson::error(null, 'Access denied', 403);
        }

        return $next($request);
    }
}
