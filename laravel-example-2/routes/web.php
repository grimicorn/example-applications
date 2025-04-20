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

Auth::routes(['register' => false]);

Route::redirect('home', 'jobs');
Route::redirect('nova', '/admin');

Route::middleware('developer')->get('dump', 'DumpController@index');

Route::middleware('auth')->group(function ($route) {
    $route->redirect('/', '/jobs');

    $route->any('styleguide', 'StyleguideController@index')->name('styleguide');

    $route->post('jobs/sort', 'JobsSortController@store')->name('jobs-sort.store');

    $route->resource('boards', 'BoardsController')->only([
        'index', 'show',
    ]);

    $route->resource('jobs', 'JobsController')->only([
        'store', 'index', 'update'
    ]);
});
