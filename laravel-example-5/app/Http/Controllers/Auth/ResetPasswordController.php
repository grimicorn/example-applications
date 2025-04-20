<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'title' => 'Reset Password',
        ])->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function sendResetResponse(Request $request, $response)
    {
        if (request()->expectsJson()) {
            return [
                'redirect' => route('dashboard', [
                    'alert' => trans($response),
                    'alert_type' => 'success',
                    'alert_timeout' => 5000,
                ]),
            ];
        }

        return redirect($this->redirectPath())
            ->with('status', trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }
}
