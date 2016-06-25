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

// Long Lived Access Token
Route::get('/getaccesstoken', 'FacebookPageController@getAccessToken');

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
    Route::group(['prefix' => '{fbpage}', 'middleware' => 'fbpage'], function () {
        // Posts Übersicht
        Route::get('/', 'FacebookPageController@show');
        // Analyse
        Route::get('analyse', 'FacebookPageController@showResults');
        // Posts nachladen
        Route::get('nachladen', 'FacebookPageController@getPosts');
        // Analyse
        Route::group(['prefix' => 'analyse'], function () {
            Route::get('/', 'FacebookPageController@showResults');
            Route::get('start', 'FacebookPageController@startAnalysis');
            Route::get('start/success', function() {
                Session::flash('success', true);
                return Redirect::back();
            });
            Route::get('start/failure', function() {
                Session::flash('failure', $_GET['exception']);
                return Redirect::back();
            });
        });
    });
});

