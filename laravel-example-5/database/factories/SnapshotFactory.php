<?php

use App\SnapshotConfiguration;
use Faker\Generator as Faker;

$factory->define(App\Snapshot::class, function (Faker $faker) {
    return [
        'snapshot_configuration_id' => function () {
            return create(SnapshotConfiguration::class)->id;
        },
        'is_baseline' => false,
        'difference' => null,
    ];
});

$factory->state(App\Snapshot::class, 'baseline', function (Faker $faker) {
    return [
        'is_baseline' => true,
    ];
});
