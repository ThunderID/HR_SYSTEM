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

Route::group(['prefix' => 'admin'], function(){
	Route::group(['namespace' => 'Auth\\'], function() {
		Route::get('/', 					['uses' => 'LoginController@getLogin',				'as' => 'hr.login']);
		Route::post('/login',				['uses' => 'LoginController@postLogin',				'as' => 'hr.postlogin']);
		Route::get('/logout',				['uses' => 'LoginController@getLogout',				'as' => 'hr.logout']);
	});

	Route::group(['namespace' => 'Dashboard\\'], function() {
		Route::get('dashboard', 			['uses' => 'DashboardController@overview',			'as' => 'hr.dashboard']);
	});

	Route::group(['namespace' => 'Organisation\\'], function() {
		Route::get('choice-organisasi',		['uses' => 'OrganisationController@getChoice',		'as' => 'hr.org.get_choice']);
	});
});	

