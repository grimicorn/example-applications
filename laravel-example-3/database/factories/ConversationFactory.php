<?php

use Faker\Generator as Faker;
use App\Support\ConversationCategoryType;

$factory->define(App\Conversation::class, function (Faker $faker) {
    return [
        'exchange_space_id' => function () {
            return factory(App\ExchangeSpace::class)->create()->id;
        },
        'resolved' => $faker->boolean(),
        'title' => $faker->words(3, true),
        'category' => ConversationCategoryType::OTHER,
    ];
});
