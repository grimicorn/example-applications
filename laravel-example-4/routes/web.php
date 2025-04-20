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

Route::get('/', function () {
    return redirect()->action('HomeController@index');
});

Auth::routes();

Route::get('/home', function() {
    return redirect()->action('HomeController@index');
});

Route::get('/support', 'SupportContactController@create');
Route::post('/support', 'SupportContactController@store');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'HomeController@index');
    Route::put('/token/generate', 'APITokenGenerateController@create');
    Route::patch('select-list', 'SelectListController@update');
    Route::patch('/default-task-due-date', 'DefaultTaskDueDateController@update');
});

Route::group(['middleware' => 'auth', 'prefix' => 'oauth2/google'], function() {
    Route::get('request', 'GoogleOAuth2Controller@request');
    Route::get('revoke', 'GoogleOAuth2Controller@revoke');
    Route::get('callback', 'GoogleOAuth2Controller@authCallback');
});

Route::group(['middleware' => 'auth:administrator', 'prefix' => '/admin'], function() {
    Route::get('/', function() {
        return view('admin.dashboard');
    })->name('adminDashboard');

    Route::get('/users', 'AdminUserListController@index');
});
