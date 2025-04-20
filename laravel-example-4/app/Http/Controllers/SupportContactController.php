<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\Support;
use Illuminate\Http\Request;
use App\Mail\DefaultMailUser;

class SupportContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();
        return view('support', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);

        \Mail::to(new DefaultMailUser)
             ->send(new Support($request->only([ 'name', 'email', 'message' ])));

        return back()->with( 'form_success', 'Thank you for contacting us, we will be in touch soon.' );
    }
}
