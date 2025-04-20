<?php

use Faker\Generator as Faker;

$factory->define(App\Expense::class, function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->states('lcs-hsf-zero')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});

$factory->state(App\Expense::class, 'lcs-empty', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->states('lcs-hsf-zero')->create()->id;
        },
        'name' => null,
        'order' => 0,
    ];
});

$factory->state(App\Expense::class, 'lcs-full', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->states('lcs-hsf-zero')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});

$factory->state(App\Expense::class, 'lcs-hsf-zero', function (Faker $faker) {
    return [
        'listing_id' => function () {
            return factory('App\Listing')->states('lcs-hsf-zero')->create()->id;
        },
        'name' => $faker->words(3, true),
        'order' => 0,
    ];
});
