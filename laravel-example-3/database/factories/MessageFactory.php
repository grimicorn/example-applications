<?php

use Faker\Generator as Faker;

$factory->define(App\Message::class, function (Faker $faker) {
    return [
        'conversation_id' => function () {
            return factory(App\Conversation::class)->create()->id;
        },
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'body' => $faker->paragraphs(3, true),
    ];
});
