<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class APITokenGenerateController extends Controller
{
    /**
     * Creates a new API token.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $user->api_token = sha1(encrypt($user->id . time() . uniqid()));
        $user->save();

        $response = [
            'api_token' => $user->api_token,
            'alert_success' => 'API Token generated successfully!',
        ];

        return back()->with($response);
    }
}
