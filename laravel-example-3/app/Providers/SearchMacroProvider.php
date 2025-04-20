<?php

namespace App\Providers;

use Laravel\Scout\Builder;
use Illuminate\Support\ServiceProvider;

class SearchMacroProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! Builder::hasMacro('aroundLatLng')) {
            Builder::macro('aroundLatLng', function ($lat, $lng, $radiusMiles = 25) {
                if (config('scout.driver') !== 'algolia') {
                    return $this;
                }

                $callback = $this->callback;

                $this->callback = function ($algolia, $query, $options) use ($lat, $lng, $radiusMiles, $callback) {
                    $options['aroundLatLng'] = (float) $lat . ',' . (float) $lng;
                    $options['aroundRadius'] = intval($radiusMiles * 1609.344);

                    if ($callback) {
                        return call_user_func(
                            $callback,
                            $algolia,
                            $query,
                            $options
                        );
                    }

                    return $algolia->search($query, $options);
                };

                return $this;
            });
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
