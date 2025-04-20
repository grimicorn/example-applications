<?php

use App\BusinessCategory;
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

$factory->define(App\UserDesiredPurchaseCriteria::class, function (\Faker\Generator $faker) {
    $data = new TestApplicationData;

    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'business_categories' => $data->getTestBusinesCategories(),
        'locations' => null,
        'asking_price_minimum' => $faker->randomNumber(6),
        'asking_price_maximum' => $faker->randomNumber(8),
        'revenue_minimum' => $faker->randomNumber(6),
        'revenue_maximum' => $faker->randomNumber(8),
        'ebitda_minimum' => $faker->randomNumber(6),
        'ebitda_maximum' => $faker->randomNumber(8),
        'pre_tax_income_minimum' => $faker->randomNumber(6),
        'pre_tax_income_maximum' => $faker->randomNumber(8),
        'discretionary_cash_flow_minimum' => $faker->randomNumber(6),
        'discretionary_cash_flow_maximum' => $faker->randomNumber(8),
    ];
});

$factory->state(App\UserDesiredPurchaseCriteria::class, 'example-1', function ($faker) {
    return [
        'locations' => null,
        'asking_price_minimum' => 1000000,
        'asking_price_maximum' => null,
        'revenue_minimum' => 750000,
        'revenue_maximum' => null,
        'ebitda_minimum' => 250000,
        'ebitda_maximum' => null,
        'pre_tax_income_minimum' => 400000,
        'pre_tax_income_maximum' => null,
        'discretionary_cash_flow_minimum' => 300000,
        'discretionary_cash_flow_maximum' => null,
        'business_categories' => [
            BusinessCategory::where('label', 'Pest Control')->get()->first()->id,
            BusinessCategory::where('label', 'Museums & Galleries')->get()->first()->id,
            BusinessCategory::where('label', 'Finance & Insurance')->get()->first()->id,
            BusinessCategory::where('label', 'Real Estate')->get()->first()->id,
        ],
    ];
});

$factory->state(App\UserDesiredPurchaseCriteria::class, 'example-2', function ($faker) {
    return [
        'locations' => null,
        'asking_price_minimum' => null,
        'asking_price_maximum' => null,
        'revenue_minimum' => null,
        'revenue_maximum' => null,
        'ebitda_minimum' => null,
        'ebitda_maximum' => null,
        'pre_tax_income_minimum' => null,
        'pre_tax_income_maximum' => null,
        'discretionary_cash_flow_minimum' => null,
        'discretionary_cash_flow_maximum' => null,
        'business_categories' => null,
    ];
});

$factory->state(App\UserDesiredPurchaseCriteria::class, 'example-3', function ($faker) {
    return [
        'locations' => null,
        'asking_price_minimum' => null,
        'asking_price_maximum' => null,
        'revenue_minimum' => null,
        'revenue_maximum' => null,
        'ebitda_minimum' => null,
        'ebitda_maximum' => null,
        'pre_tax_income_minimum' => null,
        'pre_tax_income_maximum' => null,
        'discretionary_cash_flow_minimum' => null,
        'discretionary_cash_flow_maximum' => null,
        'business_categories' => null,
    ];
});

$factory->state(App\UserDesiredPurchaseCriteria::class, 'example-4', function ($faker) {
    return [
        'locations' => null,
        'asking_price_minimum' => null,
        'asking_price_maximum' => null,
        'revenue_minimum' => null,
        'revenue_maximum' => null,
        'ebitda_minimum' => null,
        'ebitda_maximum' => null,
        'pre_tax_income_minimum' => null,
        'pre_tax_income_maximum' => null,
        'discretionary_cash_flow_minimum' => null,
        'discretionary_cash_flow_maximum' => null,
        'business_categories' => null,
    ];
});
