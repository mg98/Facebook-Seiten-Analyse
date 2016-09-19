<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

// Nützliche Helfer
Route::get('php', function() { phpinfo(); });
Route::get('getaccesstoken', 'FacebookPageController@getAccessToken');
Route::get('clearcache', function() { return Cache::flush(); });

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
    Route::post('neu', 'FacebookPageController@add');

    // Facebook Seiten Ansicht
    Route::group(['prefix' => '{fbpage}', 'middleware' => 'fbpage'], function () {

        // Seite Übersicht
        Route::get('/', 'FacebookPageController@show');
        // Posts nachladen
        Route::get('nachladen', 'FacebookPageController@getPosts');

        // Seite zurücksetzen
        Route::get('reset', 'FacebookPageController@reset');
        // Seite löschen
        Route::post('delete', 'FacebookPageController@remove');

        // Ergebnisse exportieren
        Route::get('export', 'UserAnalysisController@export');

        // Facebook Seiten im Post markieren
        Route::group(['prefix' => '{fbpost}', 'middleware' => 'fbpost'], function () {
            Route::get('markieren', 'PostMarkingController@index');
            Route::post('markieren', 'PostMarkingController@add');
            Route::get('{mark}/demarkieren', 'PostMarkingController@remove');
        });

        // Analyse
        Route::group(['prefix' => 'analyse'], function () {

            Route::get('/', 'UserAnalysisController@showResults');

            Route::group(['prefix' => 'start'], function () {
                Route::get('/', 'UserAnalysisController@start');
                Route::get('success', function() {
                    Session::flash('success', true);
                    return Redirect::back();
                });
                Route::get('failure', function() {
                    Session::flash('failure', $_GET['exception']);
                    return Redirect::back();
                });
            });

            Route::get('stop', 'UserAnalysisController@stop');

            Route::get('reset', 'UserAnalysisController@reset');

        });

    });

});
