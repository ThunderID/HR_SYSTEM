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
Route::group(['domain' => 'rcmsystem.co'], function()
{
Route::group(['middleware' => 'csrfverify'], function()
{

	Route::group(['namespace' => 'Auth\\'], function() 
	{
		Route::get('/',		 					['uses' => 'LoginController@getLogin',					'as' => 'hr.login']);
		
		Route::get('/login', 					['uses' => 'LoginController@getLogin',					'as' => 'hr.login.get']);
		
		Route::post('/login',					['uses' => 'LoginController@postLogin',					'as' => 'hr.login.post']);
	});

	Route::group(['before' => 'hr_acl'], function()
	{	
		// ------------------------------------------------------------------------------------
		// LOGOUT AND PASSWORD PAGE
		// ------------------------------------------------------------------------------------

		Route::group(['namespace' => 'Auth\\'], function() 
		{
			Route::get('/password',				['uses' => 'PasswordController@getPassword',			'as' => 'hr.password.get']);
			
			Route::post('/password',			['uses' => 'PasswordController@postPassword',			'as' => 'hr.password.post']);
			
			Route::get('/logout',				['uses' => 'LoginController@getLogout',					'as' => 'hr.logout.get']);
		});

		// ------------------------------------------------------------------------------------
		// PERSON WIDGET RESOURCE
		// ------------------------------------------------------------------------------------
		
		Route::resource('person/widgets',		'PersonWidgetController',								['names' => ['index' => 'hr.person.widgets.index', 'create' => 'hr.person.widgets.create', 'store' => 'hr.person.widgets.store', 'show' => 'hr.person.widgets.show', 'edit' => 'hr.person.widgets.edit', 'update' => 'hr.person.widgets.update', 'destroy' => 'hr.person.widgets.delete']]);

		// ------------------------------------------------------------------------------------
		// RECORD LOG RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('recordlogs',			'RecordLogController',									['names' => ['index' => 'hr.recordlogs.index', 'create' => 'hr.recordlogs.create', 'store' => 'hr.recordlogs.store', 'show' => 'hr.recordlogs.show', 'edit' => 'hr.recordlogs.edit', 'update' => 'hr.recordlogs.update', 'destroy' => 'hr.recordlogs.delete']]);

		// ------------------------------------------------------------------------------------
		// NOTIFICATION QUEUE BATCH RESOURCE
		// ------------------------------------------------------------------------------------

		Route::resource('queue',				'QueueController',										['names' => ['index' => 'hr.queue.index', 'create' => 'hr.queue.create', 'store' => 'hr.queue.store', 'show' => 'hr.queue.show', 'edit' => 'hr.queue.edit', 'update' => 'hr.queue.update', 'destroy' => 'hr.queue.delete']]);


		// ------------------------------------------------------------------------------------
		// SYSTEM SETTINGS
		// ------------------------------------------------------------------------------------

		Route::resource('applications',			'ApplicationController',								['names' => ['index' => 'hr.applications.index', 'create' => 'hr.applications.create', 'store' => 'hr.applications.store', 'show' => 'hr.applications.show', 'edit' => 'hr.applications.edit', 'update' => 'hr.applications.update', 'destroy' => 'hr.applications.delete']]);
		
		Route::group(['namespace' => 'Application\\'], function() 
		{
			// ------------------------------------------------------------------------------------
			// APP MENUS RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('menus',			'MenuController',										['names' => ['index' => 'hr.application.menus.index', 'create' => 'hr.application.menus.create', 'store' => 'hr.application.menus.store', 'show' => 'hr.application.menus.show', 'edit' => 'hr.application.menus.edit', 'update' => 'hr.application.menus.update', 'destroy' => 'hr.application.menus.delete']]);
		});
		
		Route::resource('authgroups',			'AuthGroupController',									['names' => ['index' => 'hr.authgroups.index', 'create' => 'hr.authgroups.create', 'store' => 'hr.authgroups.store', 'show' => 'hr.authgroups.show', 'edit' => 'hr.authgroups.edit', 'update' => 'hr.authgroups.update', 'destroy' => 'hr.authgroups.delete']]);

		
		Route::get('error/messages',			['uses' => 'InfoMessageDeviceController@index', 		'as' => 'hr.infomessage.index']);
		
		Route::resource('ip/whitelist',			'IPWhitelistController',								['names' => ['index' => 'hr.ipwhitelists.index', 'create' => 'hr.ipwhitelists.create', 'store' => 'hr.ipwhitelists.store', 'show' => 'hr.ipwhitelists.show', 'edit' => 'hr.ipwhitelists.edit', 'update' => 'hr.ipwhitelists.update', 'destroy' => 'hr.ipwhitelists.delete']]);

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
			// DOCUMENTS RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('documents',			'DocumentController',								['names' => ['index' => 'hr.documents.index', 'create' => 'hr.documents.create', 'store' => 'hr.documents.store', 'show' => 'hr.documents.show', 'edit' => 'hr.documents.edit', 'update' => 'hr.documents.update', 'destroy' => 'hr.documents.delete']]);

			// ------------------------------------------------------------------------------------
			// WORKLEAVES RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('workleaves',			'WorkleaveController',								['names' => ['index' => 'hr.workleaves.index', 'create' => 'hr.workleaves.create', 'store' => 'hr.workleaves.store', 'show' => 'hr.workleaves.show', 'edit' => 'hr.workleaves.edit', 'update' => 'hr.workleaves.update', 'destroy' => 'hr.workleaves.delete']]);
			
			// ------------------------------------------------------------------------------------
			// POLICIES RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('policies',				'PolicyController',									['names' => ['index' => 'hr.policies.index', 'create' => 'hr.policies.create', 'store' => 'hr.policies.store', 'show' => 'hr.policies.show', 'edit' => 'hr.policies.edit', 'update' => 'hr.policies.update', 'destroy' => 'hr.policies.delete']]);

			// ------------------------------------------------------------------------------------
			// AUTHENTICATION RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('authentications',		'AuthenticationController',							['names' => ['index' => 'hr.authentications.index', 'create' => 'hr.authentications.create', 'store' => 'hr.authentications.store', 'show' => 'hr.authentications.show', 'edit' => 'hr.authentications.edit', 'update' => 'hr.authentications.update', 'destroy' => 'hr.authentications.delete']]);

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
		
				Route::group(['namespace' => 'Chart\\', 'prefix' => 'chart'], function() 
				{
					// ------------------------------------------------------------------------------------
					// CALENDARS FOR CHART RESOURCE
					// ------------------------------------------------------------------------------------

					Route::resource('calendars',	'CalendarController',								['names' => ['index' => 'hr.chart.calendars.index', 'create' => 'hr.chart.calendars.create', 'store' => 'hr.chart.calendars.store', 'show' => 'hr.chart.calendars.show', 'edit' => 'hr.chart.calendars.edit', 'update' => 'hr.chart.calendars.update', 'destroy' => 'hr.chart.calendars.delete']]);

					// ------------------------------------------------------------------------------------
					// WORKLEAVES FOR CHART RESOURCE
					// ------------------------------------------------------------------------------------

					Route::resource('workleaves',	'WorkleaveController',								['names' => ['index' => 'hr.chart.workleaves.index', 'create' => 'hr.chart.workleaves.create', 'store' => 'hr.chart.workleaves.store', 'show' => 'hr.chart.workleaves.show', 'edit' => 'hr.chart.workleaves.edit', 'update' => 'hr.chart.workleaves.update', 'destroy' => 'hr.chart.workleaves.delete']]);
				});

			});

		Route::group(['namespace' => 'Workleave\\', 'prefix' => 'workleave'], function() 
		{
			// ------------------------------------------------------------------------------------
			// BATCH FOR WORKLEAVE RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('batch',				'BatchController',									['names' => ['index' => 'hr.workleaves.batch.index', 'create' => 'hr.workleaves.batch.create', 'store' => 'hr.workleaves.batch.store', 'show' => 'hr.workleaves.batch.show', 'edit' => 'hr.workleaves.batch.edit', 'update' => 'hr.workleaves.batch.update', 'destroy' => 'hr.workleaves.batch.delete']]);
		});

		Route::group(['namespace' => 'Calendar\\', 'prefix' => 'calendar'], function() 
		{
			// ------------------------------------------------------------------------------------
			// CHARTS FOR CALENDAR RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('charts',				'ChartController',									['names' => ['index' => 'hr.calendar.charts.index', 'create' => 'hr.calendar.charts.create', 'store' => 'hr.calendar.charts.store', 'show' => 'hr.calendar.charts.show', 'edit' => 'hr.calendar.charts.edit', 'update' => 'hr.calendar.charts.update', 'destroy' => 'hr.calendar.charts.delete']]);
		});

		Route::group(['namespace' => 'Document\\', 'prefix' => 'document'], function() 
		{
			// ------------------------------------------------------------------------------------
			// CHARTS FOR WORKLEAVE RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('templates',			'TemplateController',								['names' => ['index' => 'hr.document.templates.index', 'create' => 'hr.document.templates.create', 'store' => 'hr.document.templates.store', 'show' => 'hr.document.templates.show', 'edit' => 'hr.document.templates.edit', 'update' => 'hr.document.templates.update', 'destroy' => 'hr.document.templates.delete']]);
		});

		Route::group(['namespace' => 'Person\\', 'prefix' => 'person'], function() 
		{

			// ------------------------------------------------------------------------------------
			// CONTACTS FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('contacts',				'ContactController',								['names' => ['index' => 'hr.person.contacts.index', 'create' => 'hr.person.contacts.create', 'store' => 'hr.person.contacts.store', 'show' => 'hr.person.contacts.show', 'edit' => 'hr.person.contacts.edit', 'update' => 'hr.person.contacts.update', 'destroy' => 'hr.person.contacts.delete']]);

			// ------------------------------------------------------------------------------------
			// RELATIVES FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('relatives',			'RelativeController',								['names' => ['index' => 'hr.person.relatives.index', 'create' => 'hr.person.relatives.create', 'store' => 'hr.person.relatives.store', 'show' => 'hr.person.relatives.show', 'edit' => 'hr.person.relatives.edit', 'update' => 'hr.person.relatives.update', 'destroy' => 'hr.person.relatives.delete']]);

			// ------------------------------------------------------------------------------------
			// WORKS FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('works',				'WorkController',									['names' => ['index' => 'hr.person.works.index', 'create' => 'hr.person.works.create', 'store' => 'hr.person.works.store', 'show' => 'hr.person.works.show', 'edit' => 'hr.person.works.edit', 'update' => 'hr.person.works.update', 'destroy' => 'hr.person.works.delete']]);

		Route::group(['middleware' => 'interfere'], function()
		{

			// ------------------------------------------------------------------------------------
			// SCHEDULES FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------
			Route::resource('schedules',			'ScheduleController',								['names' => ['index' => 'hr.person.schedules.index', 'create' => 'hr.person.schedules.create', 'store' => 'hr.person.schedules.store', 'show' => 'hr.person.schedules.show', 'edit' => 'hr.person.schedules.edit', 'update' => 'hr.person.schedules.update', 'destroy' => 'hr.person.schedules.delete']]);
	
			// ------------------------------------------------------------------------------------
			// WORKLEAVES FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('workleaves',			'WorkleaveController',								['names' => ['index' => 'hr.person.workleaves.index', 'create' => 'hr.person.workleaves.create', 'store' => 'hr.person.workleaves.store', 'show' => 'hr.person.workleaves.show', 'edit' => 'hr.person.workleaves.edit', 'update' => 'hr.person.workleaves.update', 'destroy' => 'hr.person.workleaves.delete']]);
		});


			// ------------------------------------------------------------------------------------
			// DOCUMENTS FOR PERSON RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('documents',			'DocumentController',								['names' => ['index' => 'hr.person.documents.index', 'create' => 'hr.person.documents.create', 'store' => 'hr.person.documents.store', 'show' => 'hr.person.documents.show', 'edit' => 'hr.person.documents.edit', 'update' => 'hr.person.documents.update', 'destroy' => 'hr.person.documents.delete']]);
		});


		Route::group(['namespace' => 'Report\\', 'prefix' => 'report'], function() 
		{
			// ------------------------------------------------------------------------------------
			// REPORT FOR ACTIVITIES (PROCESS LOG) RESOURCE
			// ------------------------------------------------------------------------------------

			Route::resource('activities',			'ActivityController',								['names' => ['index' => 'hr.report.activities.index', 'create' => 'hr.report.activities.create', 'store' => 'hr.report.activities.store', 'show' => 'hr.report.activities.show', 'edit' => 'hr.report.activities.edit', 'update' => 'hr.report.activities.update', 'destroy' => 'hr.report.activities.delete']]);

			Route::group(['namespace' => 'Activity\\', 'prefix' => 'activity'], function() 
			{
				// ------------------------------------------------------------------------------------
				// REPORT FOR LOG ACTIVITIES (PROCESS LOG PER PERSON) RESOURCE
				// ------------------------------------------------------------------------------------

				Route::resource('logs',				'LogController',									['names' => ['index' => 'hr.activity.logs.index', 'create' => 'hr.activity.logs.create', 'store' => 'hr.activity.logs.store', 'show' => 'hr.activity.logs.show', 'edit' => 'hr.activity.logs.edit', 'update' => 'hr.activity.logs.update', 'destroy' => 'hr.activity.logs.delete']]);
			});


			Route::group(['middleware' => 'interfere'], function()
			{
				// ------------------------------------------------------------------------------------
				// REPORT FOR ATTENDANCES (PROCESS LOG) RESOURCE
				// ------------------------------------------------------------------------------------

				Route::resource('attendances',		'AttendanceController',								['names' => ['index' => 'hr.report.attendances.index', 'create' => 'hr.report.attendances.create', 'store' => 'hr.report.attendances.store', 'show' => 'hr.report.attendances.show', 'edit' => 'hr.report.attendances.edit', 'update' => 'hr.report.attendances.update', 'destroy' => 'hr.report.attendances.delete']]);
			});
		});

		});
	});	
}); 

Route::group(['namespace' => 'Organisation\\'], function()
{
	// ------------------------------------------------------------------------------------
	// AJAX DOCUMENT FOR PERSON
	// ------------------------------------------------------------------------------------

	Route::any('documents-list', 					['uses' => 'DocumentController@ajax', 				'as' => 'hr.documents.list']);

	// ------------------------------------------------------------------------------------
	// AJAX GET LAST NIK PERSON
	// ------------------------------------------------------------------------------------
	Route::any('get-last-nik',						['uses' => 'PersonController@getLastNIK',			'as' => 'hr.person.getlastnik']);

	// ------------------------------------------------------------------------------------
	// CHECK BATCH PROGESS ON WORKLEAVE
	// ------------------------------------------------------------------------------------

	Route::any('workleave/progress', 				['uses' => 'WorkleaveController@batchprogress', 	'as' => 'hr.batch.workleaves']);
});

Route::group(['namespace' => 'Organisation\\Branch\\', 'prefix' => 'branch'], function() 
{
	// ------------------------------------------------------------------------------------
	// FINGER FOR BRANCH RESOURCE
	// ------------------------------------------------------------------------------------

	Route::resource('fingers',						'FingerController',									['names' => ['index' => 'hr.branch.fingers.index', 'create' => 'hr.branch.fingers.create', 'store' => 'hr.branch.fingers.store', 'show' => 'hr.branch.fingers.show', 'edit' => 'hr.branch.fingers.edit', 'update' => 'hr.branch.fingers.update', 'destroy' => 'hr.branch.fingers.delete']]);

	// ------------------------------------------------------------------------------------
	// PERSON CHART WORKLEAVE IN FORM WORK
	// ------------------------------------------------------------------------------------	
	Route::group(['namespace' => 'Chart\\', 'prefix' => 'chart'], function()
	{
		Route::any('workleave',					['uses' => 'WorkleaveController@getChartWorkleave',		'as' => 'hr.person.chart.workleave']);
	});
});

Route::group(['namespace' => 'Organisation\\Calendar\\', 'prefix' => 'calendar', 'middleware' => 'interfere'], function() 
{
	// ------------------------------------------------------------------------------------
	// SCHEDULES FOR CALENDAR RESOURCE
	// ------------------------------------------------------------------------------------

	Route::resource('schedules',					'ScheduleController',								['names' => ['index' => 'hr.calendar.schedules.index', 'create' => 'hr.calendar.schedules.create', 'store' => 'hr.calendar.schedules.store', 'show' => 'hr.calendar.schedules.show', 'edit' => 'hr.calendar.schedules.edit', 'update' => 'hr.calendar.schedules.update', 'destroy' => 'hr.calendar.schedules.delete']]);

	// ------------------------------------------------------------------------------------
	// CHECK BATCH PROGESS ON SCHEDULE
	// ------------------------------------------------------------------------------------

	Route::any('progress', 							['uses' => 'ScheduleController@batchprogress', 		'as' => 'hr.batch.schedules']);
});

Route::group(['namespace' => 'Organisation\\Person\\', 'prefix' => 'person'], function() 
{
	// ------------------------------------------------------------------------------------
	// AJAX SCHEDULES FOR PERSON
	// ------------------------------------------------------------------------------------

	Route::any('schedules-list',					['uses' => 'ScheduleController@ajax',				'as' => 'hr.person.schedule.ajax']);

	// ------------------------------------------------------------------------------------
	// AJAX FOLLOWS FOR PERSON
	// ------------------------------------------------------------------------------------

	Route::any('follows-list',						['uses' => 'WorkController@ajax',					'as' => 'hr.person.work.ajax']);

	// ------------------------------------------------------------------------------------
	// CHECK BATCH PROGESS ON PERSON WORKLEAVE
	// ------------------------------------------------------------------------------------

	Route::any('workleave/progress', 				['uses' => 'WorkleaveController@batchprogress', 	'as' => 'hr.batch.person.workleaves']);

	// ------------------------------------------------------------------------------------
	// CHECK BATCH PROGESS ON PERSON SCHEDULE
	// ------------------------------------------------------------------------------------

	Route::any('schedule/progress', 				['uses' => 'ScheduleController@batchprogress', 		'as' => 'hr.batch.person.schedules']);
});


Route::group(['namespace' => 'Organisation\\Workleave\\'], function()
{
	// ------------------------------------------------------------------------------------
	// AJAX STORE BATCH
	// ------------------------------------------------------------------------------------

	Route::post('batch/workleave/',					['uses'	=> 'BatchController@store', 				'as' => 'hr.ajax.batch']);

	// ------------------------------------------------------------------------------------
	// AJAX STORE FOLLOW WORKLEAVE
	// ------------------------------------------------------------------------------------

	Route::post('follow/workleave/',				['uses'	=> 'WorkController@store', 					'as' => 'hr.workleaves.works.store']);
});

// ------------------------------------------------------------------------------------
// API ROUTE
// ------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------
// GLOBAL API
// ------------------------------------------------------------------------------------

Route::group(['namespace' => 'Tracker\\'], function() 
{
	// ------------------------------------------------------------------------------------
	// TIME SYNC ABSENT SYSTEM VERSION = 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/time/test/',					['uses' => 'TimeController@test',					'as' => 'hr.time.test']);
});

// ------------------------------------------------------------------------------------
// TRACKER API
// ------------------------------------------------------------------------------------

Route::group(['namespace' => 'Organisation\\Person\\'], function() 
{
	// ------------------------------------------------------------------------------------
	// SAVE LOG ON TRACKER VERSION < 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/activity/logs/',				['uses' => 'LogController@store',					'as' => 'hr.log.store']);
});

Route::group(['namespace' => 'Tracker\\'], function() 
{
	// ------------------------------------------------------------------------------------
	// ADMIN LOGIN TRACKER VERSION < 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/tracker/setting/',				['uses' => 'LoginController@postlogin',				'as' => 'hr.tracker.post']);

	// ------------------------------------------------------------------------------------
	// APPS TEST ROUTE TRACKER VERSION < 1.0 (COMPATIBILITY FOR VERSION 1.0)
	// ------------------------------------------------------------------------------------

	Route::post('api/tracker/test/',				['uses' => 'LoginController@testlogin',				'as' => 'hr.tracker.test']);

	// ------------------------------------------------------------------------------------
	// AUTO UPDATE ABSENT SYSTEM VERSION = 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/tracker/update/',				['uses' => 'LoginController@updateversion',			'as' => 'hr.tracker.update']);

	// ------------------------------------------------------------------------------------
	// SAVE LOG ON TRACKER VERSION = 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/tracker/verse3/',				['uses' => 'TimeController@testv3',					'as' => 'hr.t3.post']);
});

// ------------------------------------------------------------------------------------
// FP API
// ------------------------------------------------------------------------------------
Route::group(['namespace' => 'FP\\'], function() 
{
	// ------------------------------------------------------------------------------------
	// TEST FP CONNECTION
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/test/',						['uses' => 'LoginController@testlogin',				'as' => 'hr.fp.test']);

	// ------------------------------------------------------------------------------------
	// ADMIN LOGIN FP VERSION > 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/setting/',					['uses' => 'LoginController@postlogin',				'as' => 'hr.fp.post']);

	// ------------------------------------------------------------------------------------
	// DISPLAY FINGER OF THE DAY ON THAT BRANCH
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/oftheday/',					['uses' => 'LoginController@fingeroftheday',		'as' => 'hr.fp.fotd']);


	// ------------------------------------------------------------------------------------
	// SYNC MASTER DATA AND LOCAL DATABASES
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/sync/',						['uses' => 'LoginController@dbsync',				'as' => 'hr.fp.dbsync']);

	// ------------------------------------------------------------------------------------
	// SAVE LOG ON FP VERSION = 1.0
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/verse3/',					['uses' => 'LogController@store',					'as' => 'hr.fp3.post']);

	// ------------------------------------------------------------------------------------
	// SAVE PERSONS' FINGER
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/enroll/',					['uses' => 'FingerController@store',				'as' => 'hr.finger.store']);


	// ------------------------------------------------------------------------------------
	// SAVE PERSONS' FINGER
	// ------------------------------------------------------------------------------------

	Route::post('api/fp/delete/',					['uses' => 'FingerController@destroy',				'as' => 'hr.finger.destroy']);
});
});

// Route::group(['prefix' => 'myip'], function()
Route::group(['domain' => 'myip.rcmsystem.co'], function()
{
	Route::get('/', function()
	{
		return $_SERVER['REMOTE_ADDR'];
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
Blade::extend(function ($value, $compiler)
{
	$pattern = $compiler->createMatcher('break_foreach');
	$replace = '<?php break; ?>';

	return preg_replace($pattern, '$1'.$replace, $value);
});
Blade::extend(function ($value, $compiler)
{
	$pattern = $compiler->createMatcher('gmdate');
	$replace = '<?php echo gmdate("H:i:s", $2); ?>';

	return preg_replace($pattern, '$1'.$replace, $value);
});