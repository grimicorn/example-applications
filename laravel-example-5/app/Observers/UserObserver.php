<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "saving" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function saving(User $user)
    {
        if ($user->first_name or $user->last_name) {
            $user->name = trim("{$user->first_name} {$user->last_name}");
        }
    }
}
