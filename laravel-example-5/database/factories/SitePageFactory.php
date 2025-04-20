<?php

use App\Site;
use Faker\Generator as Faker;
use Tests\Support\SitemapPageUrls;

$factory->define(App\SitePage::class, function (Faker $faker) {
    return [
        'site_id' => function () {
            return create(Site::class)->id;
        },
        'url' => (new SitemapPageUrls)->get()->random(),
        'ignored' => false,
        'difference_threshold' => null,
        'needs_review' => false,
    ];
});
