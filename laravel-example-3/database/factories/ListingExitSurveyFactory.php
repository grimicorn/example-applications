<?php

use Faker\Generator as Faker;

$factory->define(App\ListingExitSurvey::class, function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory(App\Listing::class)->create()->id;
        },
        'final_sale_price' => $faker->numberBetween(10000, 500000),
        'overall_experience_rating' => $faker->numberBetween(1, 5),
        'overall_experience_feedback' => $faker->paragraphs(3, true),
        'products_services' => $faker->paragraphs(3, true),
        'participant_message' => $faker->paragraphs(3, true),
        'sale_completed' => false,
    ];
});
