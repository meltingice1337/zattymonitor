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



Route::group(array('prefix' => 'api'), function(){
	Route::get('/login', ['uses' => 'ApiController@getLogin']);
	Route::post('/send', ['uses' => 'ApiController@postSend']);

	// for js
	Route::get('/{id}/status', ['middleware' => 'auth', 'uses' => 'ApiController@getStatus', 'as' => 'api.status']);
	Route::get('/{id}/lastactivity', ['middleware' => 'auth', 'uses' => 'ApiController@getLastActivity', 'as' => 'api.lastactivity']);
	Route::get('/{id}/nickname', ['middleware' => 'auth', 'uses' => 'ApiController@getNickname', 'as' => 'api.nickname']);

	Route::get('/{id}/stats', ['middleware' => 'auth', 'uses' => 'ApiController@getStatistics', 'as' => 'api.statistics']);
	Route::get('/{id}/apps', ['middleware' => 'auth', 'uses' => 'ApiController@getApps', 'as' => 'api.apps']);
	Route::get('/{id}/screenshot', ['middleware' => 'auth', 'uses' => 'ApiController@getScreenshot', 'as' => 'api.screenshot']);
	Route::get('/{id}/screenshot/{number}', ['middleware' => 'auth', 'uses' => 'ApiController@getImage', 'as' => 'api.image']);

});

Route::get('/', ['uses' => 'UserController@getIndex']);

// Authentication routes...
Route::get('login', ['uses' => 'Auth\AuthController@getLogin', 'as' => 'login.get']);
Route::post('login', ['uses' => 'Auth\AuthController@postLogin', 'as' => 'login.post']);
Route::get('logout', ['uses' => 'Auth\AuthController@getLogout', 'as' => 'logout.get']);

// Registration routes...
Route::get('register', ['uses' => 'Auth\AuthController@getRegister', 'as' => 'register.get']);
Route::post('register',['uses' =>  'Auth\AuthController@postRegister', 'as' => 'register.post']);




Route::group(['middleware' => 'auth', 'prefix' => 'user'], function(){
	Route::get('/', ['uses' => 'UserController@getComputers', 'as' => 'user.computers.get']);
	Route::get('/profile', ['uses' => 'UserController@getProfile', 'as' => 'user.profile.get']);
	Route::post('/profile', ['uses' => 'UserController@postProfile', 'as' => 'user.profile.post']);
	Route::get('/computer/{id}', ['uses' => 'UserController@getComputer', 'as' => 'user.computer.get']);
	Route::get('/computer/{id}/screenshots', ['uses' => 'UserController@getScreenshots', 'as' => 'user.computer.screenshots.get']);
	Route::get('/computer/{id}/statistics', ['uses' => 'UserController@getStatistics', 'as' => 'user.computer.statistics.get']);

});