<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch ($locale, Request $request) {
        $supported = array_keys(config('locales.supported')); // nanti kita buat config/locales.php
        if (!in_array($locale, $supported)) {
            abort(400);
        }

        $request->session()->put('locale', $locale);

        // optional: set cookie
        // return redirect()->back()->withCookie(cookie()->forever('locale', $locale));
        return redirect()->back();
    }
}
