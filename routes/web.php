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
    return view('welcome');
});

Auth::routes();

Route::prefix('api')->group(function () {
    Route::prefix('facebook')->group(function () {
        Route::get('login', 'SocialAuthFacebookController@redirect')->name('facebookLogin');
        Route::get('callback', 'SocialAuthFacebookController@callback')->name('facebookCallback');
        Route::post('deauth', 'SocialAuthFacebookController@deauth');
    });
});

Route::get('/home', 'HomeController@index')->name('home');
