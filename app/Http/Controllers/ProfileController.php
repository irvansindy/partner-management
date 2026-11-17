<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('users.profile', [
            'user' => $request->user()->loadMissing(['roles', 'office', 'division', 'dept', 'jobTitle']),
        ]);
    }
}
