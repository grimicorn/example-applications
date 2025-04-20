<?php

use Faker\Generator as Faker;

$factory->define(App\Revenue::class, function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});

$factory->state(App\Revenue::class, 'lcs-empty', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->create()->id;
        },
        'name' => null,
        'order' => 0,
    ];
});

$factory->state(App\Revenue::class, 'lcs-full', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});

$factory->state(App\Revenue::class, 'lcs-hsf-zero', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});
