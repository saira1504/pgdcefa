<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $email = request()->get('email');
        $user = User::where('email', $email)->first();

        $passwordRules = [
            'required',
            'string',
            'min:8',
            'confirmed',
        ];

        if ($user) {
            if ($user->role === 'aprendiz') {
                $passwordRules[] = 'max:15';
                $passwordRules[] = 'regex:/^[a-zA-Z0-9]+$/'; // Solo letras y números para aprendiz
            } else if ($user->role === 'admin' || $user->role === 'superadmin') {
                $passwordRules[] = 'regex:/^[a-zA-Z0-9]+$/'; // Solo letras y números para admin y superadmin
            }
        }

        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => $passwordRules,
        ];
    }

    /**
     * Get the password reset validation messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.regex' => 'La contraseña solo puede contener letras y números.',
            'password.max' => 'La contraseña no puede tener más de :max caracteres.',
        ];
    }
}
