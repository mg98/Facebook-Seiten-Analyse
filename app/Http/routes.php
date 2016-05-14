<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('404', function() {
    return view('notfound');
});

// Authentication routes...
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

// Startseite
Route::get('/', array('before' => 'auth', 'uses' => 'HomeController@index'));
Route::get('/', 'HomeController@index');

// Eingeloggter Bereich
Route::group(['middleware' => 'auth'], function () {
    // Seite hinzufügen
    Route::get('neu', function() {
        return view('fbpage/new');
    });
    Route::post('neu', 'FacebookPageController@store');

    // Facebook Seiten Ansicht
    Route::group(['prefix' => '{fbpage}'], function () {
        Route::get('/', 'FacebookPageController@show');
        Route::get('getposts', 'FacebookPageController@getPosts');
    });
});
