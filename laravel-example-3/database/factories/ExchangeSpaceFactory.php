<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;
use App\Support\ExchangeSpaceDealType;
use App\Support\ExchangeSpaceStatusType;

$factory->define(App\ExchangeSpace::class, function (Faker $faker) {
    $status = $faker->randomElement(ExchangeSpaceStatusType::getValues());
    $deal = $faker->randomElement(ExchangeSpaceDealType::getValues());
    return [
        'title' => $faker->words(3, true),
        'status' => $status,
        'deal' => $deal,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'listing_id' => function () {
            return factory(App\Listing::class)->create()->id;
        },
        'historical_financials_public' => $faker->boolean(),
    ];
});

$factory->state(App\ExchangeSpace::class, 'inquiry', function ($faker) {
    return [
        'status' => ExchangeSpaceStatusType::INQUIRY,
    ];
});

$factory->state(App\ExchangeSpace::class, 'completed', function ($faker) {
    return [
        'deal' => ExchangeSpaceDealType::COMPLETE,
        'status' => ExchangeSpaceStatusType::COMPLETED,
    ];
});


$factory->state(App\ExchangeSpace::class, 'not-inquiry', function ($faker) {
    return [
        'status' => $faker->randomElement(
            array_diff(
                ExchangeSpaceStatusType::getValues(),
                [ExchangeSpaceStatusType::INQUIRY]
            )
        ),
        'deal' => $faker->randomElement(
            array_diff(
                ExchangeSpaceDealType::getValues(),
                [ExchangeSpaceDealType::COMPLETE]
            )
        ),
    ];
});

$factory->state(App\ExchangeSpace::class, 'not-inquiry-or-completed', function ($faker) {
    return [
        'status' => $faker->randomElement(
            array_diff(
                ExchangeSpaceStatusType::getValues(),
                [
                    ExchangeSpaceStatusType::INQUIRY,
                    ExchangeSpaceStatusType::COMPLETED,
                ]
            )
        ),
        'deal' => $faker->randomElement(
            array_diff(
                ExchangeSpaceDealType::getValues(),
                [ExchangeSpaceDealType::COMPLETE]
            )
        ),
    ];
});


$factory->state(App\ExchangeSpace::class, 'current_user', function ($faker) {
    return [
        'user_id' => Auth::id(),
    ];
});
