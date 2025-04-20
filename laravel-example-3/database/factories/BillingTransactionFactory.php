<?php

use Faker\Generator as Faker;
use App\Support\User\BillingTransactionType;

$factory->define(App\BillingTransaction::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'invoice_id' => function () {
            return factory('Laravel\Spark\LocalInvoice')->create()->id;
        },
        'label' => 'Monthly Subscription',
        'description' => null,
        'amount' => 500,
        'type' => BillingTransactionType::MONTHLY_SUBSCRIPTION,
        'provider_id' => null,
    ];
});
