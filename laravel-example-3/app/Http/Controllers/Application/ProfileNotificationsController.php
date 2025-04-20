<?php

namespace App\Http\Controllers\Application;

use App\Support\HasForms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileNotificationsController extends Controller
{
    use HasForms;

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('app.sections.profile.notifications.edit', [
            'pageTitle' => 'Profile',
            'section' => 'profile',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $settings = Auth::user()->emailNotificationSettings;

        // We need to convert the values to boolean.
        $notifications = collect($request->get('emailNotificationSettings', []))->only($settings->getFillable());
        $settings->update($notifications->toArray());

        return back()->with('status', 'Your notifications have been successfully updated!')
                     ->with('success', true);
    }
}
