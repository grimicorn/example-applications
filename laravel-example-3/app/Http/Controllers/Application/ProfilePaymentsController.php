<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilePaymentsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('app.sections.profile.payments.edit', [
            'pageTitle' => 'Profile',
            'section' => 'profile',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }
}
