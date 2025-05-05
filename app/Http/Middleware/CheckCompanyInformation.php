<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckCompanyInformation
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
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login'); // fallback jika belum login
        }
        
        if ($user->roles->pluck('name')->first() === 'user') {
            if (!$user->companyInformation) {
                // Jika role user dan belum mengisi company info
                return redirect()->route('create-partner');
            } else {
                // Jika role user dan sudah isi company info
                return redirect()->route('home');
            }
        }

        // Jika role bukan 'user' (misal: admin, superadmin)
        return $next($request);
    }
}
