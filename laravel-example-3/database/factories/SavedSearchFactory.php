<?php

use Faker\Generator as Faker;
use Tests\Support\TestApplicationData;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\SavedSearch::class, function (\Faker\Generator $faker) {
    $data = new TestApplicationData;

    return [
        'name' => title_case($faker->words(3, true)),
        'keyword' => null,
        'zip_code' => $faker->postcode,
        'business_categories' => $data->getTestBusinesCategories(),
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'zip_code_radius' => $faker->numberBetween(5, 50),
        'asking_price_min' => $asking_price_min = $faker->randomFloat(2, 0, 10000),
        'asking_price_max' => $faker->randomFloat(2, $asking_price_min + 1000, 100000),
    ];
});

$factory->state(App\SavedSearch::class, 'empty', function (Faker $faker) {
    return [
        'keyword' => null,
        'zip_code' => null,
        'business_categories' => null,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'zip_code_radius' => null,
        'asking_price_min' => null,
        'asking_price_max' => null,
    ];
});

$factory->state(App\SavedSearch::class, 'asking-price-min-only', function (Faker $faker) {
    return [
        'keyword' => null,
        'zip_code' => null,
        'business_categories' => null,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'zip_code_radius' => null,
        'asking_price_max' => null,
    ];
});

$factory->state(App\SavedSearch::class, 'asking-price-max-only', function (Faker $faker) {
    return [
        'keyword' => null,
        'zip_code' => null,
        'business_categories' => null,
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'zip_code_radius' => null,
        'asking_price_min' => null,
    ];
});

$factory->state(App\SavedSearch::class, 'empty', function (Faker $faker) {
    return [
        'transaction_type' => null,
        'listing_updated_at' => null,
        'keyword' => null,
        'city' => null,
        'state' => null,
        'zip_code' => null,
        'business_categories' => null,
        'zip_code_radius' => null,
        'asking_price_min' => null,
        'asking_price_max' => null,
        'transaction_type' => null,
        'listing_updated_at' => null,
        'city' => null,
        'state' => null,
        'cash_flow_min' => null,
        'cash_flow_max' => null,
        'pre_tax_income_min' => null,
        'pre_tax_income_max' => null,
        'ebitda_min' => null,
        'ebitda_max' => null,
        'revenue_min' => null,
        'revenue_max' => null,
    ];
});
