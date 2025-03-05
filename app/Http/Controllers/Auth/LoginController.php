<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
{
    $role = auth()->user()->role; // Obtén el rol del usuario autenticado

    switch ($role) {
        case 'admin':
            return route('admin.dashboard'); // Redirige al panel de admin
        case 'editores':
            return route('editores.dashboard'); // Redirige al panel de trabajador
        case 'aprendiz':
            return route('aprendiz.dashboard'); // Redirige al panel de mayordomo
        default:
            return route('home'); // Redirige al home si no tiene un rol específico
    }
}
}
