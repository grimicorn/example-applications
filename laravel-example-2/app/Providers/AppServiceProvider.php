<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Database\MachinesOnBoards;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MachinesOnBoards::class, function () {
            return new MachinesOnBoards;
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
