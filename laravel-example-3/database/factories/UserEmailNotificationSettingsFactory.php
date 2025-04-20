<?php

use Faker\Generator as Faker;

$factory->define(App\UserEmailNotificationSettings::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'enable_all' => true,
        'enable_due_diligence' => true,
        'enable_login' => true,
        'due_diligence_digest' => true,
        'blog_posts' => true,
    ];
});
