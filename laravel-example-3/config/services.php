<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'authy' => [
        'secret' => env('AUTHY_SECRET'),
        'disable_sms' => env('AUTHY_DISABLE_SMS', false),
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'monthly_plan_id' => env('STRIPE_MONTHLY_PLAN_ID', 'monthly-299'),
        'monthly_plan_id_small' => env('STRIPE_MONTHLY_PLAN_ID_SMALL', 'monthly-99'),
        'per_listing_plan_id' => env('STRIPE_PER_LISTING_PLAN_ID', 'prod_8dwNQ5b9abOTuA'),
    ],

    'stripe-test' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_TEST_KEY', env('STRIPE_KEY')),
        'secret' => env('STRIPE_TEST_SECRET', env('STRIPE_SECRET')),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'url' => env('RECAPTCHA_URL'),
    ],

    'mapquest' => [
        'api_key' => env('MAPQUEST_API_KEY', ''),
    ]
];
