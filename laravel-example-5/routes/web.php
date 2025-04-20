<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::permanentRedirect('/', 'login');
Auth::routes(['verify' => true,]);

if (!config('srcwatch.registration_enabled')) {
    Route::permanentRedirect('/register', 'login');
}

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::resource('profile', 'ProfileController')
        ->only(['edit','update','destroy'])
        ->parameters([
            'profile' => 'user',
        ]);

    Route::resource('site/snapshots', 'SiteSnapshotsController')
        ->only(['update'])
        ->names([
            'update' => 'site.snapshots.update'
        ])
        ->parameters([
            'snapshots' => 'site',
        ]);

    Route::resource('site/baselines', 'SiteBaselinesController')
        ->only(['update'])
        ->names([
            'update' => 'site.baselines.update'
        ])
        ->parameters([
            'baselines' => 'site',
        ]);


    Route::resource('site/pages', 'SitePageController')
        ->only(['show'])
        ->names([
            'show' => 'site.pages.show'
        ]);


    Route::resource('sites', 'SiteController');

    Route::resource('notifications', 'NotificationsController')
        ->only([
            'index',
            'destroy',
        ]);

    Route::resource('notifications/read', 'NotificationReadController')
        ->only([
            'update',
            'destroy',
        ])
        ->parameters([
            'read' => 'notification'
        ])
        ->names([
            'update' => 'notifications.read.update',
            'destroy' => 'notifications.read.destroy',
        ]);



    Route::resource('site/pages/ignore', 'SitePageIgnoreController')
        ->only(['update', 'destroy'])
        ->names([
            'update' => 'site.pages.ignore.update',
            'destroy' => 'site.pages.ignore.destroy',
        ])
        ->parameters([
            'ignore' => 'page',
        ]);

    Route::patch('sites/pages/{id}/threshold/update', 'SitePageDifferenceThresholdController@update')
        ->name('site.pages.threshold.update');

    Route::patch('sites/pages/{snapshot}/baseline', 'SitePageBaselineController@update')
        ->name('site.pages.baseline.update');
});

Route::group([], base_path('routes/test.php'));

Route::get('admin/styleguide', 'StyleguideController@index')
    ->middleware('admin')
    ->name('styleguide.index');
