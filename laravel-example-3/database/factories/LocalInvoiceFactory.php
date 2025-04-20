<?php

use Faker\Generator as Faker;

$factory->define(\Laravel\Spark\LocalInvoice::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'team_id' => null,
        'provider_id' => $faker->word,
        'total' => $faker->randomNumber(2),
        'tax' => 0,
        'card_country' => 'US',
        'billing_state' => 'MO',
        'billing_zip' => 63103,
        'billing_country' => 'US',
        'vat_id' => null,
    ];
});
