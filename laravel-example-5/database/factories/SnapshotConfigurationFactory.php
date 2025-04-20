<?php

use Faker\Generator as Faker;
use App\SitePage;

$factory->define(App\SnapshotConfiguration::class, function (Faker $faker) {
    return [
        'site_page_id' => function () {
            return create(SitePage::class)->id;
        },
        'width' => 1400,
        'needs_review' => false,
    ];
});
