<?php

namespace App\Providers;

use App\User;
use Google_Client;
use App\Mail\DefaultMailUser;
use App\Mail\AdminNewUserSignup;
use App\Notifications\NewUserSignup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('Google_Client', function ($app) {
            $client = new Google_Client();
            $client->setAuthConfig(base_path('client_secret.json'));
            $client->addScope(\Google_Service_Tasks::TASKS);
            $client->addScope(\Google_Service_Tasks::TASKS_READONLY);
            $client->setRedirectUri(action('GoogleOAuth2Controller@authCallback'));
            $client->setAccessType('offline');

            return $client;
        });

        User::created(function (User $user) {
            \Mail::to(new DefaultMailUser)->send(new AdminNewUserSignup($user));
            $user->notify(new NewUserSignup($user));
        });

        if ($this->app->environment('production')) {
            $this->app->register(\Jenssegers\Rollbar\RollbarServiceProvider::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
