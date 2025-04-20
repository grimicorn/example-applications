<?php

namespace App\Support\Watchlist;

use App\User;
use App\Support\Notification\HasNotifications;

class RefreshForUserNotification
{
    use HasNotifications;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function dispatch()
    {
        // Store the individual notifications
        $this->user->savedSearches->each(function ($search) {
            $search->newListings->each(function ($listing) use ($search) {
                $this->dispatchSavedSearchNotification($search, $listing);
            });
        });

        // Send the digest
        $this->dispatchSavedSearchDigestNotification($this->user);
    }
}
