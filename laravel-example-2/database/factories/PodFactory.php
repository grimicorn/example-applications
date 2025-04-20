<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Pod;
use Faker\Generator as Faker;

$factory->define(Pod::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->word()),
    ];
});
