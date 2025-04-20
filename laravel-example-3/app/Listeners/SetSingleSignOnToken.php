<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetSingleSignOnToken
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $token = str_random(32);
        $event->user->single_sign_on_token = $token;
        $event->user->timestamps = false; // This stops the timestamps from being updated
        $event->user->save();
        session()->put('single_sign_on_token', $token);
    }
}
