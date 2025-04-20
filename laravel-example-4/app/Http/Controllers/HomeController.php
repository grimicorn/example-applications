<?php

namespace App\Http\Controllers;

use Auth;
use App\Google\Tasks;
use App\Google\OAuth2;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use OAuth2;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $google_access_token_expired = is_null($user->google_access_token);
        $list_options = (new Tasks())->listOptions();

        return view('home', compact('user', 'google_access_token_expired', 'list_options'));
    }
}
