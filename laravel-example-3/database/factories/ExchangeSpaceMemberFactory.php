<?php

use Faker\Generator as Faker;
use App\Support\ExchangeSpace\MemberRole;

$factory->define(App\ExchangeSpaceMember::class, function (Faker $faker) {
    return [
        'role' => $faker->randomElement(MemberRole::getValues()),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'exchange_space_id' => function () {
            return factory(App\ExchangeSpace::class)->states('not-inquiry-or-completed')->create()->id;
        },
        'approved' => $faker->boolean(),
        'dashboard' => $faker->boolean(),
        'active' => true,
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'buyer', function ($faker) {
    return [
        'role' => MemberRole::BUYER,
        'approved' => true,
        'active' => true,
    ];
});


$factory->state(App\ExchangeSpaceMember::class, 'seller', function ($faker) {
    return [
        'role' => MemberRole::SELLER,
        'approved' => true,
        'active' => true,
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'buyer-advisor', function ($faker) {
    return [
        'role' => MemberRole::BUYER_ADVISOR,
    ];
});


$factory->state(App\ExchangeSpaceMember::class, 'seller-advisor', function ($faker) {
    return [
        'role' => MemberRole::SELLER_ADVISOR,
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'not-seller', function ($faker) {
    $roles = MemberRole::getValues();

    if ($key = array_search(MemberRole::SELLER, $roles) !== false) {
        unset($roles[ $key ]);
    }

    return [
        'role' => $faker->randomElement($roles),
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'not-buyer', function ($faker) {
    $roles = MemberRole::getValues();

    if ($key = array_search(MemberRole::BUYER, $roles) !== false) {
        unset($roles[ $key ]);
    }

    return [
        'role' => $faker->randomElement($roles),
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'not-seller-or-buyer', function ($faker) {
    $roles = MemberRole::getValues();

    // Remove seller role.
    if ($sellerKey = array_search(MemberRole::SELLER, $roles) !== false) {
        unset($roles[ $sellerKey ]);
    }

    // Remove buyer role.
    if ($buyerKey = array_search(MemberRole::BUYER, $roles) !== false) {
        unset($roles[ $buyerKey ]);
    }

    return [
        'role' => $faker->randomElement($roles),
    ];
});


$factory->state(App\ExchangeSpaceMember::class, 'approved', function ($faker) {
    return [
        'approved' => true,
        'active' => true,
        'pending' => false,
    ];
});

$factory->state(App\ExchangeSpaceMember::class, 'dashboard', function ($faker) {
    return [
        'dashboard' => true,
    ];
});
