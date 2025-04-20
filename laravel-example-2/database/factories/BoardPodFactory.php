<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\BoardPod;
use Faker\Generator as Faker;
use App\Domain\Database\SeederHelper;

$factory->define(BoardPod::class, function (Faker $faker) {
    return [
        'pod_id' => function () {
            return (new SeederHelper)->getRandomPod()->id;
        },
        'board_id' => function () {
            return (new SeederHelper)->getRandomBoard()->id;
        },
        'sort_order' => rand(1, 10),
    ];
});
