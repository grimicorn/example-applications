<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\CheckSingleSignOnToken::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Laravel\Spark\Http\Middleware\CreateFreshApiToken::class,
            \App\Http\Middleware\OnOffToBoolean::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\LogoutDeletedUsers::class,
            \App\Http\Middleware\ConvertNaNStringsToNull::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],

        'developer' => [
            \App\Http\Middleware\Developer::class,
        ],

        'exchange-space-seller' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class,
            \App\Http\Middleware\CheckIsExchangeSpaceSeller::class,
        ],

        'exchange-space-member' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class,
            \App\Http\Middleware\CheckIsExchangeSpaceMember::class,
        ],

        'conversation-member' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class,
            \App\Http\Middleware\CheckIsConversationMember::class,
        ],

        'listing-owner' => [
            \App\Http\Middleware\CheckListingOwner::class
        ],

        'exchange-space-historical-financials-access' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class,
            \App\Http\Middleware\CheckExchangeSpaceHistoricalFinancialsAccess::class,
        ],

        'exchange-space-adjusted-financials-trends-access' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class,
            \App\Http\Middleware\CheckExchangeSpaceAdjustedFinancialsTrendsAccess::class,
        ],

        'exchange-space-closed' => [
            \App\Http\Middleware\HandleClosedExchangeSpace::class
        ],

        'favorite-owner' => [
            \App\Http\Middleware\CheckFavoriteOwner::class
        ],

        'saved-search-owner' => [
            \App\Http\Middleware\CheckSavedSearchOwner::class
        ],

        'can-access-profile' => [
            \App\Http\Middleware\CheckCanAccessProfile::class,
        ],

        'listing-publish-access' => [
            \App\Http\Middleware\ListingPublishAccess::class,
        ]
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'dev' => \Laravel\Spark\Http\Middleware\VerifyUserIsDeveloper::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'hasTeam' => \Laravel\Spark\Http\Middleware\VerifyUserHasTeam::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'subscribed' => \Laravel\Spark\Http\Middleware\VerifyUserIsSubscribed::class,
        'teamSubscribed' => \Laravel\Spark\Http\Middleware\VerifyTeamIsSubscribed::class,
    ];
}
