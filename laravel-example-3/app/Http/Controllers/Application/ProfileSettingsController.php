<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileSettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('app.sections.profile.settings.edit', [
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
        $user = Auth::user();

        // Validate
        $this->validate($request, [
            'current_password' => 'required|is_current_password:' . $user->id,
            'password' => 'required|confirmed',
        ]);

        // Update user.
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('status', 'Your profile has been successfully updated!')
                     ->with('success', true);
    }
}
