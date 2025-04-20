<?php

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ImageSearchController;
use App\Http\Controllers\LocationRatingController;
use App\Http\Controllers\UserCurrentLocationController;
use App\Http\Controllers\LocationFromGoogleMapLinkController;

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

Route::resource('locations', LocationController::class)
    ->missing(function (Request $request) {
        $segment = $request->route('location');

        if (!is_numeric($segment)) {
            return Redirect::route('locations.index');
        }

        $location = Location::find($request->route('location'));
        if (!$location || !$location->slug) {
            return Redirect::route('locations.index');
        }

        return Redirect::to(str_replace($segment, $location->slug, $request->getUri()));
    })
    ->middleware('auth');

Route::put('/location-rating/{location}', [LocationRatingController::class, 'update'])
    ->name('location-rating')
    ->middleware('auth');
Route::patch('/location-rating/{location}', [LocationRatingController::class, 'update'])
    ->name('location-rating')
    ->middleware('auth');

Route::get('/location-from-google-map-link', [LocationFromGoogleMapLinkController::class, 'create'])
    ->name('location-from-google-map-link.create')
    ->middleware('auth');
Route::post('/location-from-google-map-link', [LocationFromGoogleMapLinkController::class, 'store'])
    ->name('location-from-google-map-link.store')
    ->middleware('auth');

Route::post('/user-location', [UserCurrentLocationController::class, 'store'])
    ->name('user-location.store')
    ->middleware('auth');

Route::get('/image-search', [ImageSearchController::class, 'index'])
    ->name('image-search.index')
    ->middleware('auth');


Auth::routes(['register' => false]);

Route::redirect('/home', '/locations')->name('home');
Route::redirect('/', '/locations');

Route::resource('search-locations', SearchLocationController::class)
    ->only([
        'store',
        'destroy',
    ])
    ->middleware('auth');
