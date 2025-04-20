<?php

namespace App\Support\ListingCompletionScore;

use Illuminate\Support\Carbon;

trait AppliesPenalty
{
    /**
     * Checks if the score should be penalized due to not having recent enough data.
     *
     * @return boolean
     */
    public function shouldPenalizeStaleData()
    {
        return (new StaleDataPenalty($this->listing))->isStale();
    }

    /**
     * Calculates the stale data penalty
     *
     * @param int $score
     * @param App\Listing $listing
     * @return int
     */
    protected function calculateStaleDatePenalty($score, $listing)
    {
        return (new StaleDataPenalty($listing))->calculate($score);
    }

    /**
     * Calculates the custom admin entered penalty.
     *
     * @param int $score
     * @param App\Listing $listing
     * @return void
     */
    protected function calculateCustomPenalty($score, $listing)
    {
        $penalty = intval(optional($listing->listingCompletionScoreTotal)->custom_penalty);

        return $score * ($penalty/100);
    }

    /**
     * Applies the penalties
     *
     * @param int $score
     * @param App\Listing
     * @param string
     * @return void
     */
    protected function calculatePenalty($score, $listing, $type)
    {
        // Get the penalties first.
        $customPenalty = $this->calculateCustomPenalty($score, $listing);
        $staleDataPenalty = $this->calculateStaleDatePenalty($score, $listing);

        // Calculate penalties
        switch ($type) {
            case 'overview':
                $score = $score - $customPenalty;
                break;

            default:
                $score = $score - $customPenalty - $staleDataPenalty;
                break;
        }


        return ($score <= 0) ? 0 : $score;
    }
}
