<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Laravel\Nova\Cards\Help;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return $user->isAdmin();
        });
    }

    /**
     * Get the cards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            // new Help,
            new \Tightenco\NovaReleases\LatestRelease,
             new \Kreitje\NovaHorizonStats\JobsPastHour,
            new \Kreitje\NovaHorizonStats\FailedJobsPastHour,
            new \Kreitje\NovaHorizonStats\Processes,
            new \Kreitje\NovaHorizonStats\Workload,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \KABBOUCHI\LogsTool\LogsTool,
            new \Christophrumpel\NovaNotifications\NovaNotifications,
            new \Tightenco\NovaReleases\AllReleases,
            new \Strandafili\NovaInstalledPackages\Tool,
            new \Sbine\RouteViewer\RouteViewer,
            new \Davidpiesse\NovaMaintenanceMode\Tool,
            \MadWeb\NovaHorizonLink\HorizonLink::useLogo()
        ];
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
