<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'email_verified_at' => null,
    ];
});

$factory->state(App\User::class, 'admin', function (Faker $faker) {
    return [
        'email' => collect(config('srcwatch.admins') ?? [])->random(),
    ];
});

$factory->state(App\User::class, 'verified', function (Faker $faker) {
    return [
        'email_verified_at' => now(),
    ];
});
