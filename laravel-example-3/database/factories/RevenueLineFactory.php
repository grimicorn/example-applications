<?php

use Faker\Generator as Faker;

$factory->define(App\RevenueLine::class, function (Faker $faker) {
    return [
        'revenue_id' => function () {
            return factory('App\Revenue')->create();
        },
        'amount' => $faker->randomFloat(2),
        'year' => null,
    ];
});

$factory->state(App\RevenueLine::class, 'lcs-empty', function (Faker $faker) {
    return [
        'revenue_id' => function () {
            return factory('App\Revenue')->states('lcs-empty')->create();
        },
        'amount' => null,
        'year' => null,
    ];
});

$factory->state(App\RevenueLine::class, 'lcs-full', function (Faker $faker) {
    return [
        'revenue_id' => function () {
            return factory('App\Revenue')->states('lcs-full')->create();
        },
        'amount' => $faker->randomFloat(2),
        'year' => null,
    ];
});

$factory->state(App\RevenueLine::class, 'lcs-hsf-zero', function (Faker $faker) {
    return [
        'revenue_id' => function () {
            return factory('App\Revenue')->states('lcs-hsf-zero')->create();
        },
        'amount' => 0,
        'year' => null,
    ];
});
