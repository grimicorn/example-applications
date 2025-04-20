<?php

use App\Listing;
use App\SavedSearch;
use Faker\Generator as Faker;

$factory->define(App\SavedSearchListing::class, function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory(Listing::class)->states('published')->create()->id;
        },
        'saved_search_id' => function () {
            return factory(SavedSearch::class)->create()->id;
        }
    ];
});
