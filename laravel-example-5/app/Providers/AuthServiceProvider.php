<?php

namespace App\Providers;

use App\Site;
use App\User;
use App\SitePage;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use App\Policies\SitePagePolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\NotificationPolicy;
use App\Notification;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\SnapshotConfiguration;
use App\Policies\SnapshotConfigurationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        Site::class => SitePolicy::class,
        SitePage::class => SitePagePolicy::class,
        User::class => UserPolicy::class,
        Notification::class => NotificationPolicy::class,
        SnapshotConfiguration::class => SnapshotConfigurationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
