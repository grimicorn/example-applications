<?php

namespace App\Http\Controllers\Application;

use Illuminate\Http\Request;
use App\Support\HasResponse;
use App\Http\Controllers\Controller;

class ProfileActiveController extends Controller
{
    use HasResponse;

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user()->fresh();

        // Make sure the user has completed all the steps required before closing their account.
        if (!$user->canCloseAccount()) {
            return back()->with('general-error', $user->cannotCloseAccountReason());
        }

        // Delete the current user.
        $user->delete();

        // Set the users email as deleted
        // This will allow them to use the email in the future.
        $uniqueKey = sha1(time() . uniqid());
        $user->email = "{$user->email}.{$uniqueKey}.deleted";
        $user->save();


        // logout the current user
        auth()->logout();

        return $this->successResponse('Account closed successfully', request(), route('home'));
    }
}
