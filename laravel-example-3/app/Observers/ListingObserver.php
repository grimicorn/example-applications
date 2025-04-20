<?php

namespace App\Observers;

use App\Listing;
use App\SavedSearch;
use Illuminate\Support\Facades\Log;

class ListingObserver
{
    /**
     * Listen to the Listing deleting event.
     *
     * @param  Listing  $listing
     * @return void
     */
    public function deleting(Listing $listing)
    {
        $listing->saveExitSurvey();
    }

    /**
     * Listen to the Listing deleted event.
     *
     * @param  Listing  $listing
     * @return void
     */
    public function deleted(Listing $listing)
    {
        // Remove the exchange spaces
        $listing->spaces->each->delete();
    }
}
