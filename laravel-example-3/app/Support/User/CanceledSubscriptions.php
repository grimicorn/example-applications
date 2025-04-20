<?php

namespace App\Support\User;

use Illuminate\Support\Carbon;
use Laravel\Spark\Subscription;
use App\Jobs\UnpublishAllUserListings;

class CanceledSubscriptions
{
    protected $dateStart;
    protected $dateEnd;

    public function __construct($dateStart = null, $dateEnd = null)
    {
        $this->dateStart = $dateStart ?? Carbon::today()->subDays(2);
        $this->dateEnd = $dateEnd ?? Carbon::today();
    }

    // Add constructor for date ranges default to today/1 day before
    public function unpublishSubscriptions()
    {
    }

    public function get()
    {
        return Subscription::where('ends_at', '>=', $this->dateStart)
        ->where('ends_at', '<', $this->dateEnd)->get();
    }

    public function getUsers()
    {
        return $this->get()->map->user;
    }

    /**
     * Unpublishes canceled user listings.
     *
     * @return void
     */
    public function unpublishListings()
    {
        $this->getUsers()->each(function ($user) {
            UnpublishAllUserListings::dispatch($user);
        });
    }
}
