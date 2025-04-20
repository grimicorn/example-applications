<?php
namespace App\Google;

use Auth;
use Google_Client;
use Google_Service_Tasks;
use Illuminate\Http\Request;

trait OAuth2
{
    /**
     * Handles the authorization callback.
     *
     * @param  Request $request [description]
     *
     * @return \Illuminate\Http\Response
     */
    public function handleAuthCallback(Request $request)
    {
        if (is_null($request->input('code'))) {
            return redirect($this->authURL());
        }

        $this->client()->authenticate($request->input('code'));

        $user = Auth::user();
        $user->google_access_token = $this->client()->getAccessToken();
        $user->save();

        return redirect()
               ->action('HomeController@index')
               ->with('alert_success', 'Google Tasks authorized successfully!');
    }

    /**
     * Revoke the current users token.
     *
     * @return \Illuminate\Http\Response
     */
    public function revokeToken()
    {
        $user = Auth::user();
        $token = $user->google_access_token;
        if ( isset( $token ) ) {
            $this->client()->setAccessToken($token);
            $this->client()->revokeToken();

            $user->google_access_token = null;
            $user->save();

            return redirect()
                   ->action('HomeController@index')
                   ->with('alert_success', 'Google Tasks access revoked successfully!');
        }

        return redirect()
               ->action('HomeController@index')
               ->with('alert_error', 'Google Tasks token not found!');
    }

    /**
     * Google client.
     *
     * @return Google_Client
     */
    public function client()
    {
        $client = resolve('Google_Client');

        $user = Auth::user();
        if (! is_null($user) and ! is_null($user->google_access_token)) {
            $client->setAccessToken($user->google_access_token);
        }

        return $client;
    }

    /**
     * The authentication URL.
     *
     * @return string
     */
    public function authURL()
    {
        return $this->client()->createAuthUrl();
    }
}
