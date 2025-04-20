<?php

use App\AbuseReportLink;
use Faker\Generator as Faker;
use App\Support\Notification\NotificationType;

$factory->define(AbuseReportLink::class, function (Faker $faker) {
    return [
        'message' => $faker->paragraphs(3, true),
        'reporter_id' => function () {
            return factory('App\User')->create()->id;
        },
        'creator_id' => function () {
            return factory('App\User')->create()->id;
        },
        'notification_type' => $faker->randomElement(
            NotificationType::getConstants()->toArray()
        ),
        'message_id' => function () {
            return factory('App\Message')->create()->id;
        },
        'reason' => $faker->paragraphs(3, true),
        'reason_details' => $faker->paragraphs(3, true),
        'message_model' => App\Message::class,
        'reported_on' => null,
    ];
});

$factory->state(AbuseReportLink::class, 'no_model', function (Faker $faker) {
    return [
        'notification_type' => collect(NotificationType::getConstants()->toArray())
        ->reject(function ($constant) {
            return $constant === NotificationType::MESSAGE;
        })->random(1)->first(),
        'message_id' => null,
        'reason' => null,
        'reason_details' => null,
        'message_model' => null,
    ];
});
