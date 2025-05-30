<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'api_token' => null,
        'role' => 'subscriber',
    ];
});

$factory->define(App\RequestLog::class, function(Faker\Generator $faker) {
    $date = Carbon\Carbon::now();

    return [
        'user_id' => factory(App\User::class)->create()->id,
        'count' => $faker->numberBetween(5, 20),
        'month' => $date->format('n'),
        'year' => $date->format('Y'),
    ];
});
