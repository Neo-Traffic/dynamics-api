<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * DYNAMIC Main API
 */

// AUTH
Route::group(['prefix' => 'auth'], function () {
	Route::post('login', "AuthController@login")->name("auth.login");
	Route::get('logout', "AuthController@logout")->name("auth.logout")->middleware("UserTokenVerification");
});

// The Keys
Route::model('key', 'App\Key');
Route::resource('keys', 'KeysController')->only([
	'index', 'show', 'store', 'update', 'destroy'
]);


// Dynamics
Route::group(['prefix' => "dynamics", "middleware" => "UserTokenVerification"], function () {
	//Weather dynamic
	Route::group(['prefix' => "weather"], function () {
		//////////////
		// Backgrounds

		// Backgrounds cities list
		Route::get("backgrounds/cities", "WeatherBackgroundController@registeredCities")->name("backgrounds.cities");

		// Background selection method
		Route::post("backgrounds/selection", "WeatherBackgroundController@setSelectionMethod")->name("backgrounds.selection");

		// Single backgrounds CRUD
		Route::model('background', 'App\WeatherBackground');
		Route::resource("backgrounds", "WeatherBackgroundController")->only([
			'index', 'show', 'store', 'destroy'
		]);
	});
});




///////////////////
// The Dynamics API

/**
 * Weather dynamic routes
 */
Route::group(['prefix' => 'weather', 'middleware' => ['APIKeyVerification:weather']], function () {
	Route::get('now/{country}/{province}/{city}', "WeatherController@now")->name("weather.now");
	Route::get('tomorrow/{country}/{province}/{city}', "WeatherController@tomorrow")->name("weather.tomorrow");
	Route::get('forecast/{country}/{province}/{city}', "WeatherController@forecast")->name("weather.forecast");
	Route::get('national', "WeatherController@national")->name("weather.national");
	Route::get('backgrounds/{support}/{country}/{province}/{city}', "WeatherBackgroundController@index");
	Route::get('backgrounds/{period}/{support}/{country}/{province}/{city}', "WeatherBackgroundController@index");
});

/**
 * News dynamic routes
 */
Route::group(['prefix' => 'news', 'middleware' => ['APIKeyVerification:news']], function () {
    Route::get('refresh', "NewsController@refresh")->name("news.refresh");
});
