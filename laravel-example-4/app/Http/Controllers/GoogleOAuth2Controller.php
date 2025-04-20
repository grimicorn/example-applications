<?php

namespace App\Http\Controllers;

use App\Google\OAuth2;
use Illuminate\Http\Request;

class GoogleOAuth2Controller extends Controller
{
    use OAuth2;

    /**
     * Requests a Google access token.
     *
     * @return \Illuminate\Http\Response
     */
    public function request()
    {
        return redirect($this->authURL());
    }

    /**
     * Handles the Google access token authentication callback.
     *
     * @return \Illuminate\Http\Response
     */
    public function authCallback(Request $request)
    {
        return $this->handleAuthCallback($request);
    }

    /**
     * Revokes a Google access token.
     *
     * @return \Illuminate\Http\Response
     */
    public function revoke()
    {
        return $this->revokeToken();
    }
}
