<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticateWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard');
                case 'aprendiz':
                    return redirect()->route('aprendiz.dashboard');
            }
        }

        return $next($request);
    }
}
