<?php

namespace App\Providers;

use Laravel\Nova\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Cards\Help;
use App\Enums\UserPermissionEnum;
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
            return $user->hasPermissionTo(UserPermissionEnum::VIEW_NOVA);
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
            new Help,
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
            \Vyuldashev\NovaPermission\NovaPermissionTool::make()->canSee(function (Request $request) {
                return $request->user()->hasAllPermissions([
                    UserPermissionEnum::CREATE_USER_ROLES,
                    UserPermissionEnum::CREATE_USER_PERMISSIONS,
                ]);
            }),
            \MadWeb\NovaTelescopeLink\TelescopeLink::useLogo('blank')::make()->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(UserPermissionEnum::VIEW_TELESCOPE);
            }),
            \MadWeb\NovaHorizonLink\HorizonLink::useLogo('blank')::make()->canSee(function (Request $request) {
                return $request->user()->hasPermissionTo(UserPermissionEnum::VIEW_HORIZON);
            }),
            new \Vms\Styleguide\Styleguide,
            \Sbine\RouteViewer\RouteViewer::make()->canSee(function (Request $request) {
                return $request->user()->isDeveloper();
            }),
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
