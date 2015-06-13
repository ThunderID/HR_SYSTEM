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

Route::group(['prefix' => ''], function()
{
	// ------------------------------------------------------------------------------------
	// LOGIN PAGE
	// ------------------------------------------------------------------------------------

	Route::group(['namespace' => 'Auth\\'], function() 
	{
		Route::get('/login', 				['uses' => 'LoginController@getLogin',				'as' => 'hr.login']);
		Route::post('/login',				['uses' => 'LoginController@postLogin',				'as' => 'hr.postlogin']);
		Route::get('/logout',				['uses' => 'LoginController@getLogout',				'as' => 'hr.logout']);
	});

	// ------------------------------------------------------------------------------------
	// LANDING PAGE (CHOOSE ORGANISATION OR CREATE ORGANISATION)
	// ------------------------------------------------------------------------------------

	Route::get('/', 						['uses' => 'LandingController@get',					'as' => 'hr.start.get']);

	// ------------------------------------------------------------------------------------
	// SHOW ORGANISATION
	// ------------------------------------------------------------------------------------

	Route::get('show',						['uses' => 'OrganisationController@show',			'as' => 'hr.organisations.show']);
});	

