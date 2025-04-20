<?php

namespace App\Observers;

use App\ListingExitSurvey;
use App\Support\ExchangeSpaceDealType;

class ListingExitSurveyObserver
{
    /**
     * Listen to the Listing Exit Survey saved event.
     *
     * @param  ListingExitSurvey  $survey
     * @return void
     */
    public function saved(ListingExitSurvey $survey)
    {
        if (!request()->get('disable_space_close')) {
            $survey->listing->spaces->where(
                'deal',
                '!=',
                ExchangeSpaceDealType::COMPLETE
            )->each->delete();
        }
    }
}
