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

Route::get('neu', [
    'middleware' => 'auth',
    'uses' => function() {
        return view('fbpage/new');
    }
]);
Route::post('neu', [
    'middleware' => 'auth',
    'uses' => 'FacebookPageController@store'
]);

Route::get('{fbpage}', [
    'middleware' => 'auth',
    'uses' => 'FacebookPageController@show'
]);
Route::get('{fbpage}/getposts', [
    'middleware' => 'auth',
    'uses' => 'FacebookPageController@getPosts'
]);
