<?php

use Faker\Generator as Faker;

$factory->define(App\ExpenseLine::class, function (Faker $faker) {
    return [
        'expense_id' => function () {
            return factory('App\Revenue')->create();
        },
        'amount' => $faker->randomFloat(2),
        'year' => null,
    ];
});

$factory->state(App\ExpenseLine::class, 'lcs-empty', function (Faker $faker) {
    return [
        'expense_id' => function () {
            return factory('App\Revenue')->create();
        },
        'amount' => null,
        'year' => null,
    ];
});

$factory->state(App\ExpenseLine::class, 'lcs-full', function (Faker $faker) {
    return [
        'expense_id' => function () {
            return factory('App\Revenue')->create();
        },
        'amount' => $faker->randomFloat(2),
        'year' => null,
    ];
});

$factory->state(App\ExpenseLine::class, 'lcs-hsf-zero', function (Faker $faker) {
    return [
        'expense_id' => function () {
            return factory('App\Revenue')->states('lcs-hsf-zero')->create();
        },
        'amount' => 0,
        'year' => null,
    ];
});
