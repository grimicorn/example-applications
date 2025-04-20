<?php

namespace App\Providers;

use App\Site;
use App\User;
use App\SitePage;
use Laravel\Horizon\Horizon;
use App\SnapshotConfiguration;
use App\Observers\SiteObserver;
use App\Observers\UserObserver;
use App\Observers\SitePageObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\BroadcastingModelObserver;
use App\Observers\SnapshotConfigurationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(BroadcastingModelObserver::class);
        User::observe(UserObserver::class);
        Site::observe(SiteObserver::class);
        Site::observe(BroadcastingModelObserver::class);
        SitePage::observe(SitePageObserver::class);
        SitePage::observe(BroadcastingModelObserver::class);
        SnapshotConfiguration::observe(SnapshotConfigurationObserver::class);

        Horizon::auth(function ($request) {
            return !!optional($request->user())->isAdmin();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
