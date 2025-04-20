<?php

namespace App\Providers;

use App\Domain\Supports\Geocoder;
use App\Domain\Supports\BestVisitTimes;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Migrations\MigrationCreator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Geocoder::class, function ($app) {
            return new Geocoder;
        });

        $this->app->singleton(BestVisitTimes::class, function ($app) {
            return new BestVisitTimes;
        });

        $this->app->when(MigrationCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
