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

$factory->define(App\User::class, function (\Faker\Generator $faker) {
    static $password;
    $data = new TestApplicationData;

    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'primary_roles' => $data->getTestPrimaryRoles(),
        'photo_id' => null,
        'listed_in_directory' => $faker->boolean,
        'work_phone' => $faker->numerify('##########'),
        'tagline' => $faker->paragraph(),
        'bio' => $faker->paragraphs($faker->numberBetween(2, 5), true),
    ];
});


$factory->state(App\User::class, 'listed', function ($faker) {
    return [
        'listed_in_directory' => true,
    ];
});

$factory->state(App\User::class, 'unlisted', function ($faker) {
    return [
        'listed_in_directory' => false,
    ];
});


$factory->state(App\User::class, 'example-1', function ($faker) {
    return [
        'first_name' => 'Thomas',
        'last_name' => 'Crown',
        'email' => 'tcrownacq@mailinator.com',
        'primary_roles' => ['Buyer','Seller'],
        'listed_in_directory' => true,
        'work_phone' => '210-555-1234',
        'tagline' => 'I acquire things.  Generally businesses, sometimes artwork.',
        'bio' => 'Originally a boxer from Scotland, I am now (or rather, was) a chisledly handsome investment banker in Manhattan.  After having to sell one business, one time (at a great price, mind you), I grew bored of it and stole some artwork.  After wrecking a sailing yacht and crashing a glider, I got to see an insurance fraud investigator naked.  I suppose it was worth it, but I\'m bored again and am now looking to buy small businesses the way your grandmother buyers porcelain curios.',
    ];
});

$factory->state(App\User::class, 'example-2', function ($faker) {
    return [
        'first_name' => 'Bobby',
        'last_name' => 'Bourcher',
        'email' => 'thewaterboy@mailinator.com',
        'primary_roles' => ['Broker'],
        'listed_in_directory' => true,
        'work_phone' => '504-555-WATR',
        'tagline' => 'Now that\'s what I call high qualtiy H2O.',
        'bio' => 'I firmly believe that proper hydration is the key to successfully selling your business.  And I\'ve been doing that now for many, many years.',
    ];
});

$factory->state(App\User::class, 'example-3', function ($faker) {
    return [
        'first_name' =>    'Joseph',
        'last_name' =>    'Elk',
        'email' =>    'joe.elk@mailinator.com',
        'primary_roles' =>    ['Seller'],
        'listed_in_directory' => false,
        'work_phone' => null,
        'tagline' => 'Looking to sell two restaurants that confusingly bear the name of either me or my father.',
        'bio' => 'Looking to sell two restaurants that confusingly bear the name of either me or my father.'
    ];
});

$factory->state(App\User::class, 'example-4', function ($faker) {
    return [
        'first_name' => 'Alex',
        'last_name' => 'Curcuru',
        'email' => 'thecuuks@mailinator.com',
        'primary_roles' => ['Advisor'],
        'listed_in_directory' => true,
        'work_phone' => '314-555-6789',
        'tagline' => 'You don\'t need a tax lawyer or a tax accountant.  You need both.
        bio "Alex Curcuru provides advice on a wide-range of tax issues and handles disputes with the IRS and Missouri Department of Revenue for both individuals and businesses. Alex’s exposure to tax matters began long before his admission to the Bar. Over the decade, he has either prepared or quality reviewed thousands of tax returns. This experience has proven invaluable in establishing a foundation for successful client advocacy and reputation for effective tax controversy representation. To date, Alex has successfully saved his clients more than a million dollars in outstanding tax liabilities.

        Previously, Alex worked for H&R Block as a tax research specialist where he answered complex federal and state tax questions on topics that included eligibility for various tax credits and deductions, residency status, sources of income and the First-Time Homebuyer Credit."',
    ];
});
