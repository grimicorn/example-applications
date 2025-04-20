<?php

Route::group(['middleware' => 'developer', 'prefix' => 'tools', 'namespace' => 'Tools'], function ($router) {
    $router->get('/watchlist/story', function () {
        return (new \App\Tools\WatchlistStory)->getResults();
    });


    $router->get('/get-client-ip', function () {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }

        dd($ipAddress, $_SERVER);
    });

    $router->get('model-to-json/list', 'ModelToJson@index')->name('model-to-json.index');
    $router->get('model-to-json', 'ModelToJson@show')->name('model-to-json.show');

    $router->get('i-take-exception-to-that', function () {
        throw new \Exception("I Take Exception To That", 1);
    });

    $router->get('/listing/completion-score', function () {
        \App\Listing::all()->chunk(20)->each(function ($listings) {
            \App\Jobs\Fix\UpdateListingCompletionScore::dispatch($listings);
        });
    });

    $router->get('error/{code}', function ($code) {
        abort($code);
    });

    $router->get('save-all', function () {
        if ($model = request()->get('model')) {
            $modelName = str_replace(['\App\\', 'App\\'], '', $model);
            $model = "\App\\{$modelName}";
            \App\Jobs\Fix\ChunkModels::dispatch($model);
            echo "Saving for {$modelName} queued.";
            return;
        }

        \App\Jobs\Fix\ChunkModels::dispatch(\App\BillingTransaction::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\BusinessCategory::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Conversation::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ConversationNotification::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\AbuseReportLink::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ExchangeSpace::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ExchangeSpaceMember::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ExchangeSpaceNotification::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Expense::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ExpenseLine::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Favorite::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\HistoricalFinancial::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Listing::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ListingCompletionScoreTotal::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\ListingExitSurvey::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\MarketingContactNotification::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Message::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Notification::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\RejectionReason::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\Revenue::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\RevenueLine::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\SavedSearch::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\SavedSearchNotification::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\SystemMessage::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\UserDesiredPurchaseCriteria::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\UserEmailNotificationSettings::class);
        \App\Jobs\Fix\ChunkModels::dispatch(\App\UserProfessionalInformation::class);
        echo 'Saving for all models queued.';
    });
});
