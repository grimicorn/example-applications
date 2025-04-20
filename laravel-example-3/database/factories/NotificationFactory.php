<?php

use Faker\Generator as Faker;
use App\Support\Notification\NotificationType;

$factory->define(App\SavedSearchNotification::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'saved_search_id' => function () {
            return factory(App\SavedSearch::class)->create()->id;
        },
        'listing_id' => function () {
            return factory(App\Listing::class)->create()->id;
        },
        'type' => \App\Support\Notification\NotificationType::SAVED_SEARCH_MATCH,
        'rule_name' => $faker->words($faker->numberBetween(1, 9), true) || "test rule",
        'read' => false,
    ];
});

$factory->define(App\ExchangeSpaceNotification::class, function (Faker $faker) {
    $deal = $faker->randomElement(\App\Support\ExchangeSpaceDealType::getValues());
    $status = $faker->randomElement(\App\Support\ExchangeSpaceStatusType::getValues());

    $seller = factory('App\User')->create();
    $buyer = factory('App\User')->create();

    // Setup the exchange space.
    $exchange_space = factory('App\ExchangeSpace')->states('inquiry')->create([
        'user_id' => $seller->id,
    ]);

    // Add the seller to the exchange space.
    $seller = factory('App\ExchangeSpaceMember')->states('seller')->create([
        'exchange_space_id' => $exchange_space->id,
        'user_id' => $seller->id,
    ]);

    // Add the buyer to the exchange space.
    $buyer = factory('App\ExchangeSpaceMember')->states('buyer')->create([
        'exchange_space_id' => $exchange_space->id,
        'user_id' => $buyer->id,
    ]);

    return [
        'user_id' => $exchange_space->user_id,
        'exchange_space_id' => $exchange_space->id,
        'type' => $this->faker->randomElement($exchange_space->getSpaceNotificationTypes()),
        'exchange_space_title' => $exchange_space->title,
        'exchange_space_deal' => $deal,
        'exchange_space_status' => $status,
        'buyer_name' => $buyer->name,
        'requested_member_name' => $faker->name(),
        'removed_member_name' => $faker->name(),
        'business_name' => $exchange_space->listing->title,
        'deal_label' => $exchange_space->deal_label,
        'read' => false,
    ];
});

$factory->state(App\ExchangeSpaceNotification::class, 'unread', function (Faker $faker) {
    return [
        'read' => false,
    ];
});

$factory->state(App\ExchangeSpaceNotification::class, 'new-inquiry', function (Faker $faker) {
    return [
        'type' => NotificationType::NEW_BUYER_INQUIRY,
    ];
});



$factory->define(App\ConversationNotification::class, function (Faker $faker) {
    $seller = factory('App\User')->create();
    $buyer = factory('App\User')->create();

    // Setup the exchange space.
    $exchange_space = factory('App\ExchangeSpace')->states('inquiry')->create([
        'user_id' => $seller->id,
    ]);

    // Add the seller to the exchange space.
    $seller = factory('App\ExchangeSpaceMember')->states('seller')->create([
        'exchange_space_id' => $exchange_space->id,
        'user_id' => $seller->id,
    ]);

    // Add the buyer to the exchange space.
    $buyer = factory('App\ExchangeSpaceMember')->states('buyer')->create([
        'exchange_space_id' => $exchange_space->id,
        'user_id' => $buyer->id,
    ]);

    // Finally...create the conversation and return it.
    $conversation = factory('App\Conversation')->create(array_merge([
        'exchange_space_id' => $exchange_space->id,
    ]));

    return [
        'user_id' => $seller->id,
        'conversation_id' => $conversation->id,
        'type' => $this->faker->randomElement($conversation->getConversationNotificationTypes()),
        'message_sender_name' => $buyer->name,
        'read' => false,
        'conversation_title' => $conversation->title,
        'exchange_space_title' => $exchange_space->title,
    ];
});

$factory->state(App\ConversationNotification::class, 'message', function (Faker $faker) {
    return [
        'type' => NotificationType::MESSAGE,
    ];
});

$factory->state(App\ConversationNotification::class, 'new', function (Faker $faker) {
    return [
        'type' => NotificationType::NEW_DILIGENCE_CENTER_CONVERSATION,
    ];
});

$factory->state(App\ConversationNotification::class, 'message', function (Faker $faker) {
    return [
        'type' => NotificationType::MESSAGE,
    ];
});

$factory->state(App\ConversationNotification::class, 'unread', function (Faker $faker) {
    return [
        'read' => false,
    ];
});
