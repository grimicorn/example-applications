<?php

namespace Tests\Support;

trait HasSavedSearchHelpers
{
    protected function matchedSearch($listing, $make = false)
    {
        $attributes = [
            'name' => 'Saved Search Name',
            'business_categories' => [
                $listing->business_category_id,
                $listing->business_sub_category_id,
            ],
            'state' => $listing->state,
            'asking_price_min' => null,
            'asking_price_max' => null,
        ];

        if ($make) {
            return factory('App\SavedSearch')->make($attributes);
        }

        return factory('App\SavedSearch')->create($attributes);
    }
}
