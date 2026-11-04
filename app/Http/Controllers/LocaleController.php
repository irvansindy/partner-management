<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch ($locale, Request $request)
    {
        $supported = array_keys(config('locales.supported'));
        if (!in_array($locale, $supported, true)) {
            abort(400);
        }

        $request->session()->put('locale', $locale);

        $returnTo = $request->query('return_to');
        if (is_string($returnTo)
            && str_starts_with($returnTo, '/')
            && !str_starts_with($returnTo, '//')
        ) {
            return redirect()->to($returnTo);
        }

        $previous = $request->headers->get('referer');
        if (!$previous) {
            return redirect('/');
        }

        if (str_starts_with($previous, '/') && !str_starts_with($previous, '//')) {
            return redirect()->to($previous);
        }

        $parts = parse_url($previous);
        if ($parts === false
            || !isset($parts['scheme'], $parts['host'])
            || !in_array(strtolower($parts['scheme']), ['http', 'https'], true)
            || strcasecmp($parts['host'], $request->getHost()) !== 0
        ) {
            return redirect('/');
        }

        $target = $parts['path'] ?? '/';
        if (isset($parts['query'])) {
            $target .= '?' . $parts['query'];
        }

        return redirect()->to($target);
    }
}
