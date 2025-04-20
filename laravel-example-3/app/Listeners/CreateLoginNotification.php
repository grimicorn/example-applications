<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Support\Notification\HasNotifications;
use App\Support\Notification\LoginNotification;
use App\Support\Notification\LoginNotificationCookie;

class CreateLoginNotification
{
    use HasNotifications;

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
        $loginCookie = new LoginNotificationCookie;

        if ($loginCookie->exists()) {
            return;
        }

        $loginCookie->set();

        // If the user was recently created then we shouldn't send this out.
        $this->dispatchNotification(new LoginNotification);
    }
}
