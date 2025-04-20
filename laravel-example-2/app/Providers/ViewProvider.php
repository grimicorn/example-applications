<?php

namespace App\Providers;

use App\Machine;
use App\Enums\JobFlag;
use App\Enums\ArtStatus;
use App\Enums\WipStatus;
use App\Enums\PickStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.app', function ($view) {
            $view->with('machines', Machine::all()->sortBy('name')->values());
            $view->with('job_statuses', collect(WipStatus::keys())->map(function ($key) {
                return [
                    'name' => WipStatus::displayNameForKey($key),
                    'key' => $key,
                ];
            })->sortBy('name')->values());

            $view->with('pick_statuses', collect(PickStatus::keys())->map(function ($key) {
                return [
                    'name' => PickStatus::displayNameForKey($key),
                    'key' => $key,
                ];
            })->sortBy('name')->values());

            $view->with('art_statuses', collect(ArtStatus::keys())->map(function ($key) {
                return [
                    'name' => ArtStatus::displayNameForKey($key),
                    'key' => $key,
                ];
            })->sortBy('name')->values());


            $view->with('job_flags', collect(JobFlag::keys())->map(function ($key) {
                return [
                    'name' => $name = JobFlag::displayNameForKey($key),
                    'key' => $key,
                    'slug' => Str::slug($name),
                ];
            })->sortBy('name')->keyBy('key'));

            $view->with('wip_statuses', collect(WipStatus::keys())->keyBy(function ($key) {
                return $key;
            })->map(function ($key) {
                return WipStatus::nameForKey($key);
            }));



            return $view;
        });
    }
}
