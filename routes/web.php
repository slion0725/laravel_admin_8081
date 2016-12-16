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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('auth');

Route::group(['middleware' => ['auth', 'featyres']], function () {

    Route::group(['prefix' => 'profile', 'as' => 'profile'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ProfileController@index']);
        Route::post('/', ['as' => 'update', 'uses' => 'ProfileController@update']);
        Route::post('/password', ['as' => 'password', 'uses' => 'ProfileController@password']);
    });

    Route::get('/accounts/datatable', 'AccountsController@datatable');
    Route::resource('accounts', 'AccountsController', ['except' => [
        'create',
    ]]);

    Route::get('/weblogs/datatable', 'WeblogsController@datatable');
    Route::resource('weblogs', 'WeblogsController', ['only' => [
        'index', 'show',
    ]]);

    Route::get('/menuitems/datatable', 'MenuitemsController@datatable');
    Route::resource('menuitems', 'MenuitemsController', ['except' => [
        'create',
    ]]);

    Route::get('/settings/datatable', 'SettingsController@datatable');
    Route::resource('settings', 'SettingsController', ['except' => [
        'create',
    ]]);

    Route::get('/menugroups/datatable', 'MenugroupsController@datatable');
    Route::resource('menugroups', 'MenugroupsController', ['except' => [
        'create',
    ]]);
});
