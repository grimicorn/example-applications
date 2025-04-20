<?php

namespace App\Observers;

use App\ListingCompletionScoreTotal;
use App\Support\ListingCompletionScore\ListingCompletionScore;

class ListingCompletionScoreTotalObserver
{
    /**
     * Listen to the ListingCompletionScoreTotal saved event.
     *
     * @param  ListingCompletionScoreTotal  $total
     * @return void
     */
    public function saved(ListingCompletionScoreTotal $total)
    {
        $total->listing->current_score_total = $total->total;

        // Get the calculations for use in display contexts.
        // Where we dont want to calculate items on the fly.
        $calc = new ListingCompletionScore($total->listing);
        $total->listing->current_score_total_percentage = $calc->totalPercentage();
        $total->listing->current_score_total_percentage_for_display = $calc->totalPercentageForDisplay();

        $total->listing->save();
    }
}
