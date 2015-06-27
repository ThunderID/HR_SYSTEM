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

// ------------------------------------------------------------------------------------
// LOGIN PAGE
// ------------------------------------------------------------------------------------

Route::group(['namespace' => 'Auth\\'], function() 
{
	Route::get('/',		 					['uses' => 'LoginController@getLogin',				'as' => 'hr.login']);
	
	Route::get('/login', 					['uses' => 'LoginController@getLogin',				'as' => 'hr.login.get']);
	
	Route::post('/login',					['uses' => 'LoginController@postLogin',				'as' => 'hr.login.post']);
});

Route::group(['before' => 'hr_acl'], function()
{
	// ------------------------------------------------------------------------------------
	// LOGOUT PAGE
	// ------------------------------------------------------------------------------------

	Route::group(['namespace' => 'Auth\\'], function() 
	{
		Route::get('/logout',					['uses' => 'LoginController@getLogout',				'as' => 'hr.logout']);
	});

	// ------------------------------------------------------------------------------------
	// LANDING PAGE (CHOOSE ORGANISATION OR CREATE ORGANISATION), SHOW ORGANISATION (STARTED WITH DASHBOARD)
	// ------------------------------------------------------------------------------------

	Route::resource('organisations',			'OrganisationController',							['names' => ['index' => 'hr.organisations.index', 'create' => 'hr.organisations.create', 'store' => 'hr.organisations.store', 'show' => 'hr.organisations.show', 'edit' => 'hr.organisations.edit', 'update' => 'hr.organisations.update', 'destroy' => 'hr.organisations.delete']]);

	Route::group(['namespace' => 'Organisation\\'], function() 
	{

		// ------------------------------------------------------------------------------------
		// BRANCHES RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('branches',				'BranchController',									['names' => ['index' => 'hr.branches.index', 'create' => 'hr.branches.create', 'store' => 'hr.branches.store', 'show' => 'hr.branches.show', 'edit' => 'hr.branches.edit', 'update' => 'hr.branches.update', 'destroy' => 'hr.branches.delete']]);

		// ------------------------------------------------------------------------------------
		// CALENDARS RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('calendars',			'CalendarController',								['names' => ['index' => 'hr.calendars.index', 'create' => 'hr.calendars.create', 'store' => 'hr.calendars.store', 'show' => 'hr.calendars.show', 'edit' => 'hr.calendars.edit', 'update' => 'hr.calendars.update', 'destroy' => 'hr.calendars.delete']]);

		// ------------------------------------------------------------------------------------
		// WORKLEAVES RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('workleaves',			'WorkleaveController',								['names' => ['index' => 'hr.workleaves.index', 'create' => 'hr.workleaves.create', 'store' => 'hr.workleaves.store', 'show' => 'hr.workleaves.show', 'edit' => 'hr.workleaves.edit', 'update' => 'hr.workleaves.update', 'destroy' => 'hr.workleaves.delete']]);

		// ------------------------------------------------------------------------------------
		// DOCUMENTS RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('documents',			'DocumentController',								['names' => ['index' => 'hr.documents.index', 'create' => 'hr.documents.create', 'store' => 'hr.documents.store', 'show' => 'hr.documents.show', 'edit' => 'hr.documents.edit', 'update' => 'hr.documents.update', 'destroy' => 'hr.documents.delete']]);

		// ------------------------------------------------------------------------------------
		// IDLES RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('idles',				'IdleController',									['names' => ['index' => 'hr.idles.index', 'create' => 'hr.idles.create', 'store' => 'hr.idles.store', 'show' => 'hr.idles.show', 'edit' => 'hr.idles.edit', 'update' => 'hr.idles.update', 'destroy' => 'hr.idles.delete']]);

		// ------------------------------------------------------------------------------------
		// PERSONS RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('persons',				'PersonController',									['names' => ['index' => 'hr.persons.index', 'create' => 'hr.persons.create', 'store' => 'hr.persons.store', 'show' => 'hr.persons.show', 'edit' => 'hr.persons.edit', 'update' => 'hr.persons.update', 'destroy' => 'hr.persons.delete']]);

		// ------------------------------------------------------------------------------------
		// UPLOAD IMAGE PERSON
		// ------------------------------------------------------------------------------------		
		Route::any('upload-image',				['uses' => 'PersonController@upload_image',			'as' => 'hr.upload.image']);

		Route::group(['namespace' => 'Branch\\', 'prefix' => 'branch'], function() 
		{

			// ------------------------------------------------------------------------------------
			// CONTACTS FOR BRANCH RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('contacts',			'ContactController',								['names' => ['index' => 'hr.branch.contacts.index', 'create' => 'hr.branch.contacts.create', 'store' => 'hr.branch.contacts.store', 'show' => 'hr.branch.contacts.show', 'edit' => 'hr.branch.contacts.edit', 'update' => 'hr.branch.contacts.update', 'destroy' => 'hr.branch.contacts.delete']]);

			// ------------------------------------------------------------------------------------
			// CHARTS FOR BRANCH RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('charts',			'ChartController',									['names' => ['index' => 'hr.branch.charts.index', 'create' => 'hr.branch.charts.create', 'store' => 'hr.branch.charts.store', 'show' => 'hr.branch.charts.show', 'edit' => 'hr.branch.charts.edit', 'update' => 'hr.branch.charts.update', 'destroy' => 'hr.branch.charts.delete']]);

			// ------------------------------------------------------------------------------------
			// APIS FOR BRANCH RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('apis',				'ApiController',									['names' => ['index' => 'hr.branch.apis.index', 'create' => 'hr.branch.apis.create', 'store' => 'hr.branch.apis.store', 'show' => 'hr.branch.apis.show', 'edit' => 'hr.branch.apis.edit', 'update' => 'hr.branch.apis.update', 'destroy' => 'hr.branch.apis.delete']]);

			// ------------------------------------------------------------------------------------
			// FINGER FOR BRANCH RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('fingers',			'FingerController',									['names' => ['index' => 'hr.branch.fingers.index', 'create' => 'hr.branch.fingers.create', 'store' => 'hr.branch.fingers.store', 'show' => 'hr.branch.fingers.show', 'edit' => 'hr.branch.fingers.edit', 'update' => 'hr.branch.fingers.update', 'destroy' => 'hr.branch.fingers.delete']]);
		
			Route::group(['namespace' => 'Chart\\', 'prefix' => 'chart'], function() 
			{

				// ------------------------------------------------------------------------------------
				// AUTHENTICATIONS FOR CHART RESOURCE
				// ------------------------------------------------------------------------------------

				Route::resource('authentications',	'AuthenticationController',						['names' => ['index' => 'hr.chart.authentications.index', 'create' => 'hr.chart.authentications.create', 'store' => 'hr.chart.authentications.store', 'show' => 'hr.chart.authentications.show', 'edit' => 'hr.chart.authentications.edit', 'update' => 'hr.chart.authentications.update', 'destroy' => 'hr.chart.authentications.delete']]);

				// ------------------------------------------------------------------------------------
				// CALENDARS FOR CHART RESOURCE
				// ------------------------------------------------------------------------------------

				Route::resource('calendars',		'CalendarController',							['names' => ['index' => 'hr.chart.calendars.index', 'create' => 'hr.chart.calendars.create', 'store' => 'hr.chart.calendars.store', 'show' => 'hr.chart.calendars.show', 'edit' => 'hr.chart.calendars.edit', 'update' => 'hr.chart.calendars.update', 'destroy' => 'hr.chart.calendars.delete']]);
			});

		});

	Route::group(['namespace' => 'Calendar\\', 'prefix' => 'calendar'], function() 
	{

		// ------------------------------------------------------------------------------------
		// SCHEDULES FOR CALENDAR RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('schedules',		'ScheduleController',									['names' => ['index' => 'hr.calendar.schedules.index', 'create' => 'hr.calendar.schedules.create', 'store' => 'hr.calendar.schedules.store', 'show' => 'hr.calendar.schedules.show', 'edit' => 'hr.calendar.schedules.edit', 'update' => 'hr.calendar.schedules.update', 'destroy' => 'hr.calendar.schedules.delete']]);

		// ------------------------------------------------------------------------------------
		// CHARTS FOR CALENDAR RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('charts',			'ChartController',										['names' => ['index' => 'hr.calendar.charts.index', 'create' => 'hr.calendar.charts.create', 'store' => 'hr.calendar.charts.store', 'show' => 'hr.calendar.charts.show', 'edit' => 'hr.calendar.charts.edit', 'update' => 'hr.calendar.charts.update', 'destroy' => 'hr.calendar.charts.delete']]);
	});

	Route::group(['namespace' => 'Workleave\\', 'prefix' => 'workleave'], function() 
	{
		// ------------------------------------------------------------------------------------
		// CHARTS FOR WORKLEAVE RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('charts',			'ChartController',										['names' => ['index' => 'hr.workleave.charts.index', 'create' => 'hr.workleave.charts.create', 'store' => 'hr.workleave.charts.store', 'show' => 'hr.workleave.charts.show', 'edit' => 'hr.workleave.charts.edit', 'update' => 'hr.workleave.charts.update', 'destroy' => 'hr.workleave.charts.delete']]);
	});

	Route::group(['namespace' => 'Document\\', 'prefix' => 'document'], function() 
	{
		// ------------------------------------------------------------------------------------
		// CHARTS FOR WORKLEAVE RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('templates',		'TemplateController',									['names' => ['index' => 'hr.document.templates.index', 'create' => 'hr.document.templates.create', 'store' => 'hr.document.templates.store', 'show' => 'hr.document.templates.show', 'edit' => 'hr.document.templates.edit', 'update' => 'hr.document.templates.update', 'destroy' => 'hr.document.templates.delete']]);
	});

	Route::group(['namespace' => 'Person\\', 'prefix' => 'person'], function() 
	{

		// ------------------------------------------------------------------------------------
		// CONTACTS FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('contacts',			'ContactController',								['names' => ['index' => 'hr.person.contacts.index', 'create' => 'hr.person.contacts.create', 'store' => 'hr.person.contacts.store', 'show' => 'hr.person.contacts.show', 'edit' => 'hr.person.contacts.edit', 'update' => 'hr.person.contacts.update', 'destroy' => 'hr.person.contacts.delete']]);

		// ------------------------------------------------------------------------------------
		// RELATIVES FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('relatives',		'RelativeController',								['names' => ['index' => 'hr.person.relatives.index', 'create' => 'hr.person.relatives.create', 'store' => 'hr.person.relatives.store', 'show' => 'hr.person.relatives.show', 'edit' => 'hr.person.relatives.edit', 'update' => 'hr.person.relatives.update', 'destroy' => 'hr.person.relatives.delete']]);

		// ------------------------------------------------------------------------------------
		// WORKS FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('works',			'WorkController',									['names' => ['index' => 'hr.person.works.index', 'create' => 'hr.person.works.create', 'store' => 'hr.person.works.store', 'show' => 'hr.person.works.show', 'edit' => 'hr.person.works.edit', 'update' => 'hr.person.works.update', 'destroy' => 'hr.person.works.delete']]);

		// ------------------------------------------------------------------------------------
		// SCHEDULES FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('schedules',		'ScheduleController',								['names' => ['index' => 'hr.person.schedules.index', 'create' => 'hr.person.schedules.create', 'store' => 'hr.person.schedules.store', 'show' => 'hr.person.schedules.show', 'edit' => 'hr.person.schedules.edit', 'update' => 'hr.person.schedules.update', 'destroy' => 'hr.person.schedules.delete']]);

		// ------------------------------------------------------------------------------------
		// AJAX SCHEDULES FOR PERSON
		// ------------------------------------------------------------------------------------

		Route::any('schedules-list',		['uses' => 'ScheduleController@ajax',				'as' => 'hr.person.schedule.ajax']);

		// ------------------------------------------------------------------------------------
		// WORKLEAVES FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('workleaves',		'WorkleaveController',								['names' => ['index' => 'hr.person.workleaves.index', 'create' => 'hr.person.workleaves.create', 'store' => 'hr.person.workleaves.store', 'show' => 'hr.person.workleaves.show', 'edit' => 'hr.person.workleaves.edit', 'update' => 'hr.person.workleaves.update', 'destroy' => 'hr.person.workleaves.delete']]);

		// ------------------------------------------------------------------------------------
		// DOCUMENTS FOR PERSON RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('documents',		'DocumentController',								['names' => ['index' => 'hr.person.documents.index', 'create' => 'hr.person.documents.create', 'store' => 'hr.person.documents.store', 'show' => 'hr.person.documents.show', 'edit' => 'hr.person.documents.edit', 'update' => 'hr.person.documents.update', 'destroy' => 'hr.person.documents.delete']]);
	});


	Route::group(['namespace' => 'Report\\', 'prefix' => 'report'], function() 
	{
		// ------------------------------------------------------------------------------------
		// REPORT FOR ATTENDANCE (PROCESS LOG) RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('attendances',		'AttendanceController',								['names' => ['index' => 'hr.report.attendances.index', 'create' => 'hr.report.attendances.create', 'store' => 'hr.report.attendances.store', 'show' => 'hr.report.attendances.show', 'edit' => 'hr.report.attendances.edit', 'update' => 'hr.report.attendances.update', 'destroy' => 'hr.report.attendances.delete']]);

		// ------------------------------------------------------------------------------------
		// REPORT FOR WAGE (PROCESS LOG) RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('wages',			'WageController',									['names' => ['index' => 'hr.report.wages.index', 'create' => 'hr.report.wages.create', 'store' => 'hr.report.wages.store', 'show' => 'hr.report.wages.show', 'edit' => 'hr.report.wages.edit', 'update' => 'hr.report.wages.update', 'destroy' => 'hr.report.wages.delete']]);
	});

	});
});	

Blade::extend(function ($value, $compiler)
{
	$pattern = $compiler->createMatcher('time_indo');
	$replace = '<?php echo date("H:i", strtotime($2)); ?>';

	return preg_replace($pattern, '$1'.$replace, $value);
});

Blade::extend(function ($value, $compiler)
{
	$pattern = $compiler->createMatcher('date_indo');
	$replace = '<?php echo date("d-m-Y", strtotime($2)); ?>';

	return preg_replace($pattern, '$1'.$replace, $value);
});
