<?php

namespace Laravel\Spark\Http\Controllers\Auth;

use Laravel\Spark\Spark;
use Illuminate\Http\Request;
use App\Marketing\HasSiteData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Laravel\Spark\Http\Controllers\Controller;
use Laravel\Spark\Contracts\Interactions\Settings\Security\DisableTwoFactorAuth;

class EmergencyLoginController extends Controller
{
    use HasSiteData;
    use RedirectsUsers;

    /**
     * Create a new emergency login controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        $this->middleware('throttle:3,1')->only('login');
    }

    /**
     * Show the form to login via the emergency token.
     *
     * @param  Request  $request
     * @return Response
     */
    public function showLoginForm(Request $request)
    {
        $data = $this->getSiteData($view = 'login-emergency-token', $title = 'Login Via Emergency Token');
        $data['errorAlertValidationMessage'] = 'Unable to login successfully. See below for errors.';

        return $request->session()->has('spark:auth:id')
                        ? view('spark::auth.login-via-emergency-token', $data)
                        : redirect('login');
    }

    /**
     * Login via the emergency token.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        if (Validator::make($request->all(), ['token' => 'required'])->fails()) {
            $request->session()->put('spark:auth:id', $request->session()->get('spark:auth:id'));
            return redirect('/login-via-emergency-token')->withErrors([
                'token' => 'The Emergency Token field is required.'
            ]);
        }


        // If there is no authentication ID stored in the session, it means that the user
        // hasn't made it through the login screen so we'll just redirect them back to
        // the login view. They must have hit the route manually via a specific URL.
        if (! $request->session()->has('spark:auth:id')) {
            return redirect('login');
        }

        $user = Spark::user()->findOrFail(
            $request->session()->pull('spark:auth:id')
        );

        // Here we will check this hash of the token against the stored hash of the reset
        // token to make sure they match. If they don't match then the emergency token
        // is invalid so we'll redirect back out with an error message for the user.
        $resetCode = $user->two_factor_reset_code;

        if (! Hash::check($request->token, $resetCode)) {
            $request->session()->put('spark:auth:id', $user->id);
            return redirect('/login-via-emergency-token')->withErrors([
                'token' => 'The Emergency Token entered was invalid.'
            ]);
        }

        // If the token was valid we will login this user after disabling the two-factor
        // authentication settings so that they don't get stuck again. They will then
        // re-enable two-factor authentication in their settings if they so choose.
        $this->disableTwoFactorAuth($user);

        Auth::login($user, $request->session()->pull(
            'spark:auth:remember',
            false

        ));

        return redirect($this->redirectPath());
    }

    /**
     * Disable two-factor authentication for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function disableTwoFactorAuth($user)
    {
        Spark::interact(DisableTwoFactorAuth::class, [$user]);

        $user->forceFill([
            'uses_two_factor_auth' => false,
        ])->save();
    }
}
