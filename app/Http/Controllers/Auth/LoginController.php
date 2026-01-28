<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        $now = now()->startOfDay();
        $validFrom = $user->valid_from ? \Carbon\Carbon::parse($user->valid_from)->startOfDay() : null;
        $validTill = $user->valid_till ? \Carbon\Carbon::parse($user->valid_till)->endOfDay() : null;

        $isValid = true;

        if ($validFrom && $now->lt($validFrom)) {
            $isValid = false;
        }

        if ($validTill && $now->gt($validTill)) {
            $isValid = false;
        }

        if (!$isValid) {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->username() => ['Akun Anda tidak aktif atau masa berlaku telah habis.'],
            ]);
        }
    }
}
