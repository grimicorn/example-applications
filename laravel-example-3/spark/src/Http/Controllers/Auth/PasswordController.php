<?php

namespace Laravel\Spark\Http\Controllers\Auth;

use App\User;
use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use Illuminate\Support\Facades\Auth;
use App\Support\RedirectsToTwoFactor;
use Illuminate\Support\Facades\Password;
use Laravel\Spark\Services\Security\Authy;
use Laravel\Spark\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Laravel\Spark\Contracts\Interactions\Settings\Security\VerifyTwoFactorAuthToken as Verify;

class PasswordController extends Controller
{
    use HasSiteData;

    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
    }

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        $this->middleware('throttle:3,1')->only('sendResetLinkEmail', 'reset');

        $this->redirectTo = Spark::afterLoginRedirect();
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('spark::auth.passwords.email', $this->getSiteData($view = 'password-reset', $title = 'Password Reset'));
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->showLinkRequestForm();
        }

        $data = $this->getSiteData(
            $view = 'password-reset',
            $title = 'Reset Password'
        );

        $user = User::where('email', $request->get('email'))->first();
        if ($user->uses_two_factor_auth) {
            // Allow authenticating via SMS
            $this->sendTwoFactorAuthSMS($user);
        }

        return view('spark::auth.passwords.reset')
               ->with(array_merge($data, ['token' => $token, 'email' => $request->email, 'user' => $user]));
    }

    protected function handleReset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $user = User::where('email', $request->get('email'))->first();

        if ($user->uses_two_factor_auth) {
            $this->verifyToken($request, $user);
        }

        return $this->reset($request);
    }

    /**
     * Send two factor auth SMS message.
     *
     * @param User $user
     * @return void
     */
    protected function sendTwoFactorAuthSMS(User $user)
    {
        app(Authy::class)->sendVerificationSMS($user->authy_id);
    }

    /**
     * Verify the given authentication token.
     *
     * @param  Request  $request
     * @param  App\User $user
     * @return Response
     */
    public function verifyToken(Request $request, User $user)
    {
        $this->validate($request, [
            'two_factor_token' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $user) {
                    // Next, we'll verify the actual token with our two-factor authentication service
                    // to see if the token is valid. If it is, we can login the user and send them
                    // to their intended location within the protected part of this application.
                    if (!Spark::interact(Verify::class, [$user, $request->two_factor_token])) {
                        return $fail('Authentication Token is invalid');
                    }
                }
            ],
        ]);

        ;
    }
}
