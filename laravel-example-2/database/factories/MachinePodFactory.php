<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\MachinePod;
use Faker\Generator as Faker;
use App\Domain\Database\SeederHelper;

$factory->define(MachinePod::class, function (Faker $faker) {
    return [
        'pod_id' => function () {
            return (new SeederHelper)->getRandomPod()->id;
        },
        'machine_id' => function () {
            return (new SeederHelper)->getRandomMachine()->id;
        },
        'sort_order' => rand(1, 10),
    ];
});
