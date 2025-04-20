<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUser;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUser  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUser $request, User $user)
    {
        $site = $request->persist();
        $status = 'Profile updated successfully';

        if (request()->expectsJson()) {
            return [
                'status' => $status,
            ];
        }

        return back()->with('status', $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        auth()->logout();

        $user->delete();

        $status = "Site \"{$user->name}\" deleted successfully";
        if (request()->expectsJson()) {
            return [
                'redirect' => route('login'),
            ];
        }

        return redirect(route('login'));
    }
}
