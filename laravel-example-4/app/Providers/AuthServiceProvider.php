<?php

namespace App\Providers;

use Auth;
use App\Auth\APIKeyGuard;
use App\Auth\AdministratorGuard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('api-key', function ($app, $name, array $config) {
            return new APIKeyGuard(
                Auth::createUserProvider($config['provider']),
                resolve('Illuminate\Http\Request')
            );
        });

        Auth::extend('administrator', function ($app, $name, array $config) {
            return new AdministratorGuard(
                Auth::createUserProvider($config['provider']),
                resolve('Illuminate\Http\Request')
            );
        });
    }
}
