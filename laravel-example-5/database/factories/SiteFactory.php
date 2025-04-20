<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Site::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return create(User::class)->id;
        },
        'sitemap_url' => url('stubs/sitemaps/sitemap-index.xml'),
        'name' => title_case($faker->words($words = 3, $asText = true)),
        'difference_threshold' => .95,
        'needs_review' => false,
    ];
});
