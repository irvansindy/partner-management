<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class SetLocale
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
        $locale = $request->session()->get('locale', config('app.locale'));
        $supportedLocales = array_keys(config('locales.supported', []));

        if (!in_array($locale, $supportedLocales, true)) {
            $locale = config('app.locale');
            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
