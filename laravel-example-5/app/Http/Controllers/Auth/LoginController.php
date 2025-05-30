<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
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
    | url '/'them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->expectsJson()) {
            return [
                'redirect' => url($this->redirectTo),
            ];
        }
    }

    protected function loggedOut(Request $request)
    {
        if ($request->expectsJson()) {
            return [
                'redirect' => url('/'),
            ];
        }
    }

    public function showLoginForm()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }
}
