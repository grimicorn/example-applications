<?php

namespace app\Support\ListingCompletionScore;

use App\Listing;
use Illuminate\Support\Carbon;

class StaleDataPenalty
{
    protected $listing;

    public function __construct(Listing $listing)
    {
        $this->listing = $listing;
    }

    /**
     * Calculates the score
     *
     * @param int $score
     * @return void
     */
    public function calculate($score)
    {
        if (!$this->isStale()) {
            return 0;
        }

        // The penalty is death...actually it is just 50%.
        return $score * .5;
    }

    /**
     * Checks if the listing is stale.
     *
     * @return boolean
     */
    public function isStale()
    {
        // If they have not entered in a value then lets just wait.
        if (is_null($this->listing->hf_most_recent_year)) {
            return false;
        }

        // If the "Most Recent Full Year Available" is 3 or more years ago
        // then the stale data penalty should be applied.
        $mostRecentyear = intval($this->listing->hf_most_recent_year->format('Y'));
        $currentYear = intval(Carbon::now()->format('Y'));

        return ($currentYear - $mostRecentyear) >= 3;
    }

    /**
     * The stale error message
     *
     *  @param boolean $isFinancials
     *
     * @return string
     */
    public function errorMessage($isFinancials = false)
    {
        $message = 'The Historical Financials portion of your LC Rating has been assessed a 50% penalty because the most recent data provided is 3 or more years old.';

        if ($isFinancials) {
            return $message;
        }

        return "{$message} Please return to Historical Financials and update your financial information.";
    }
}
