<?php

use Faker\Generator as Faker;

$factory->define(App\Favorite::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'listing_id' => function () {
            return factory(App\Listing::class)->states('published')->create()->id;
        }
    ];
});
