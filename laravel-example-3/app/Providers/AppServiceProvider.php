<?php

namespace App\Providers;

use App\User;
use App\Listing;
use App\Message;
use App\SavedSearch;
use App\Conversation;
use App\ExchangeSpace;
use App\ListingExitSurvey;
use Laravel\Horizon\Horizon;
use Spatie\MediaLibrary\Media;
use App\Observers\UserObserver;
use Laravel\Spark\LocalInvoice;
use App\Observers\ListingObserver;
use App\Observers\MessageObserver;
use Illuminate\Support\Facades\URL;
use App\ListingCompletionScoreTotal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Blade;
use App\Observers\SavedSearchObserver;
use App\Observers\ConversationObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\ExchangeSpaceObserver;
use Illuminate\Support\Facades\Validator;
use App\Observers\ListingExitSurveyObserver;
use App\Observers\ListingCompletionScoreTotalObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!app()->environment('testing', 'local')) {
            URL::forceScheme('https');
        }

        /**
         * Model observers.
         */
        User::observe(UserObserver::class);
        Listing::observe(ListingObserver::class);
        ListingExitSurvey::observe(ListingExitSurveyObserver::class);
        ExchangeSpace::observe(ExchangeSpaceObserver::class);
        Message::observe(MessageObserver::class);
        Conversation::observe(ConversationObserver::class);
        SavedSearch::observe(SavedSearchObserver::class);
        ListingCompletionScoreTotal::observe(ListingCompletionScoreTotalObserver::class);

        /**
         * Custom Validation - Current Password
         */
        Validator::extend('is_current_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check(
                $value,
                User::findOrFail($parameters[0])->password
            );
        });

        /**
         * Custom Validation - Listing Photos Limit
         */
        Validator::extend('listing_photos_under_limit', function ($attribute, $value, $parameters, $validator) {
            $newCount = isset($value['new']) ? count($value['new']) : 0;
            $oldCount = isset($value['old']) ? count($value['old']) : 0;
            $deleteCount = isset($value['delete']) ? count($value['delete']) : 0;
            $totalCount = ($newCount + $oldCount) - $deleteCount;

            return $totalCount <= 8;
        });

        /**
         * Set Currency locale
         */
        setlocale(LC_MONETARY, 'en_US.UTF-8');

        /**
         * Enables horizon for developers
         */
        Horizon::auth(function ($request) {
            if ($this->app->environment('local')) {
                return true;
            }

            if (!auth()->check() || !auth()->user()->isDeveloper()) {
                return  false;
            }

            return true;
        });

        /**
         * Notifications for queue issues.
         */
        Horizon::routeMailNotificationsTo(
            'dholloran@matchboxdesigngroup.com'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->environment('production') and !$this->app->environment('testing')) {
            $this->app->register('Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider');
            $this->app->register('PrettyRoutes\ServiceProvider');
        }

        if ($this->app->environment('local')) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }

        // if ($this->app->environment('local', 'testing')) {
        //     $this->app->register(\BoxedCode\Laravel\Scout\DatabaseEngineServiceProvider::class);
        // }
    }
}
