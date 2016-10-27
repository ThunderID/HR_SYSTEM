<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Config, Session, App, View, Route, Redirect, Request, Input;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Models\Person;
use App\Models\Work;
use App\Models\WorkAuthentication;
use App\Models\Organisation;
use App\Models\Policy;

class RouteServiceProvider extends ServiceProvider {

use \Illuminate\Foundation\Bus\DispatchesCommands;
use \Illuminate\Foundation\Validation\ValidatesRequests;

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	public function register()
	{
		// ACL
		App::singleton('hr_acl', function()
		{
			$routes_acl = [
							'hr.organisations.index'						=> 1,
							'hr.organisations.show'							=> 1,
							'hr.organisations.create'						=> 2,
							'hr.organisations.store'						=> 2,
							'hr.organisations.edit'							=> 3,
							'hr.organisations.update'						=> 3,
							'hr.organisations.delete'						=> 4,

							'hr.branches.index'								=> 5,
							'hr.branches.show'								=> 5,
							'hr.branches.create'							=> 6,
							'hr.branches.store'								=> 6,
							'hr.branches.edit'								=> 7,
							'hr.branches.update'							=> 7,
							'hr.branches.delete'							=> 8,

							'hr.branch.charts.index'						=> 9,
							'hr.branch.charts.show'							=> 9,
							'hr.branch.charts.create'						=> 10,
							'hr.branch.charts.store'						=> 10,
							'hr.branch.charts.edit'							=> 11,
							'hr.branch.charts.update'						=> 11,
							'hr.branch.charts.delete'						=> 12,

							'hr.chart.calendars.index'						=> 13,
							'hr.chart.calendars.show'						=> 13,
							'hr.chart.calendars.create'						=> 14,
							'hr.chart.calendars.store'						=> 14,
							'hr.chart.calendars.edit'						=> 15,
							'hr.chart.calendars.update'						=> 15,
							'hr.chart.calendars.delete'						=> 16,

							'hr.chart.workleaves.index'						=> 13,
							'hr.chart.workleaves.show'						=> 13,
							'hr.chart.workleaves.create'					=> 14,
							'hr.chart.workleaves.store'						=> 14,
							'hr.chart.workleaves.edit'						=> 15,
							'hr.chart.workleaves.update'					=> 15,
							'hr.chart.workleaves.delete'					=> 16,

							'hr.branch.apis.index'							=> 17,
							'hr.branch.apis.show'							=> 17,
							'hr.branch.apis.create'							=> 18,
							'hr.branch.apis.store'							=> 18,
							'hr.branch.apis.edit'							=> 19,
							'hr.branch.apis.update'							=> 19,
							'hr.branch.apis.delete'							=> 20,

							'hr.branch.fingers.index'						=> 21,
							'hr.branch.fingers.show'						=> 21,
							'hr.branch.fingers.create'						=> 22,
							'hr.branch.fingers.store'						=> 22,
							'hr.branch.fingers.edit'						=> 23,
							'hr.branch.fingers.update'						=> 23,
							'hr.branch.fingers.delete'						=> 24,

							'hr.branch.contacts.index'						=> 25,
							'hr.branch.contacts.show'						=> 25,
							'hr.branch.contacts.create'						=> 26,
							'hr.branch.contacts.store'						=> 26,
							'hr.branch.contacts.edit'						=> 27,
							'hr.branch.contacts.update'						=> 27,
							'hr.branch.contacts.delete'						=> 28,

							'hr.calendars.index'							=> 29,
							'hr.calendars.show'								=> 29,
							'hr.calendars.create'							=> 30,
							'hr.calendars.store'							=> 30,
							'hr.calendars.edit'								=> 31,
							'hr.calendars.update'							=> 31,
							'hr.calendars.delete'							=> 32,

							'hr.calendar.schedules.index'					=> 33,
							'hr.calendar.schedules.show'					=> 33,
							'hr.calendar.schedules.create'					=> 34,
							'hr.calendar.schedules.store'					=> 34,
							'hr.calendar.schedules.edit'					=> 35,
							'hr.calendar.schedules.update'					=> 35,
							'hr.calendar.schedules.delete'					=> 36,
							'hr.batch.schedules'							=> 33,

							'hr.calendar.charts.index'						=> 13,
							'hr.calendar.charts.show'						=> 13,
							'hr.calendar.charts.create'						=> 14,
							'hr.calendar.charts.store'						=> 14,
							'hr.calendar.charts.edit'						=> 15,
							'hr.calendar.charts.update'						=> 15,
							'hr.calendar.charts.delete'						=> 16,

							'hr.workleaves.index'							=> 37,
							'hr.workleaves.show'							=> 37,
							'hr.workleaves.create'							=> 38,
							'hr.workleaves.store'							=> 38,
							'hr.workleaves.edit'							=> 39,
							'hr.workleaves.update'							=> 39,
							'hr.workleaves.delete'							=> 40,

							'hr.documents.index'							=> 41,
							'hr.documents.show'								=> 41,
							'hr.documents.create'							=> 42,
							'hr.documents.store'							=> 42,
							'hr.documents.edit'								=> 43,
							'hr.documents.update'							=> 43,
							'hr.documents.delete'							=> 44,
							'hr.documents.list'								=> 41,

							'hr.document.templates.index'					=> 41,
							'hr.document.templates.show'					=> 41,
							'hr.document.templates.create'					=> 42,
							'hr.document.templates.store'					=> 42,
							'hr.document.templates.edit'					=> 43,
							'hr.document.templates.update'					=> 43,
							'hr.document.templates.delete'					=> 44,
							
							'hr.policies.index'								=> 45,
							'hr.policies.show'								=> 45,
							'hr.policies.create'							=> 46,
							'hr.policies.store'								=> 46,
							'hr.policies.edit'								=> 47,
							'hr.policies.update'							=> 47,
							'hr.policies.delete'							=> 48,

							//49-52 application
							'hr.applications.index'							=> 49,
							'hr.applications.show'							=> 49,
							'hr.applications.create'						=> 50,
							'hr.applications.store'							=> 50,
							'hr.applications.edit'							=> 51,
							'hr.applications.update'						=> 51,
							'hr.applications.delete'						=> 52,

							//53-56 menu 
							'hr.application.menus.index'					=> 53,
							'hr.application.menus.show'						=> 53,
							'hr.application.menus.create'					=> 54,
							'hr.application.menus.store'					=> 54,
							'hr.application.menus.edit'						=> 55,
							'hr.application.menus.update'					=> 55,
							'hr.application.menus.delete'					=> 56,

							//57-60 authgroup
							'hr.authgroups.index'							=> 49,
							'hr.authgroups.show'							=> 49,
							'hr.authgroups.create'							=> 50,
							'hr.authgroups.store'							=> 50,
							'hr.authgroups.edit'							=> 51,
							'hr.authgroups.update'							=> 51,
							'hr.authgroups.delete'							=> 52,

							'hr.persons.index'								=> 61,
							'hr.persons.show'								=> 61,
							'hr.persons.create'								=> 62,
							'hr.persons.store'								=> 62,
							'hr.persons.edit'								=> 63,
							'hr.persons.update'								=> 63,
							'hr.persons.delete'								=> 64,

							'hr.person.works.index'							=> 65,
							'hr.person.works.show'							=> 65,
							'hr.person.works.create'						=> 66,
							'hr.person.works.store'							=> 66,
							'hr.person.works.edit'							=> 67,
							'hr.person.works.update'						=> 67,
							'hr.person.works.delete'						=> 68,

							'hr.person.work.ajax'							=> 66,

							'hr.authentications.index'						=> 69,
							'hr.authentications.show'						=> 69,
							'hr.authentications.create'						=> 70,
							'hr.authentications.store'						=> 70,
							'hr.authentications.edit'						=> 71,
							'hr.authentications.update'						=> 71,
							'hr.authentications.delete'						=> 72,
				
							'hr.person.schedules.index'						=> 73,
							'hr.person.schedules.show'						=> 73,
							'hr.person.schedules.create'					=> 74,
							'hr.person.schedules.store'						=> 74,
							'hr.person.schedules.edit'						=> 75,
							'hr.person.schedules.update'					=> 75,
							'hr.person.schedules.delete'					=> 76,
							
							'hr.person.schedule.ajax'						=> 73,

							'hr.person.workleaves.index'					=> 77,
							'hr.person.workleaves.show'						=> 77,
							'hr.person.workleaves.create'					=> 78,
							'hr.person.workleaves.store'					=> 78,
							'hr.person.workleaves.edit'						=> 79,
							'hr.person.workleaves.update'					=> 79,
							'hr.person.workleaves.delete'					=> 80,

							'hr.workleaves.batch.create'					=> 78,
							'hr.workleaves.batch.store'						=> 78,
							'hr.workleaves.works.store'						=> 78,
							'hr.ajax.batch'									=> 78,

							'hr.person.documents.index'						=> 81,
							'hr.person.documents.show'						=> 81,
							'hr.person.documents.create'					=> 82,
							'hr.person.documents.store'						=> 82,
							'hr.person.documents.edit'						=> 83,
							'hr.person.documents.update'					=> 83,
							'hr.person.documents.delete'					=> 84,

							'hr.person.contacts.index'						=> 85,
							'hr.person.contacts.show'						=> 85,
							'hr.person.contacts.create'						=> 86,
							'hr.person.contacts.store'						=> 86,
							'hr.person.contacts.edit'						=> 87,
							'hr.person.contacts.update'						=> 87,
							'hr.person.contacts.delete'						=> 88,
				
							'hr.person.relatives.index'						=> 89,
							'hr.person.relatives.show'						=> 89,
							'hr.person.relatives.create'					=> 90,
							'hr.person.relatives.store'						=> 90,
							'hr.person.relatives.edit'						=> 91,
							'hr.person.relatives.update'					=> 91,
							'hr.person.relatives.delete'					=> 92,

							'hr.report.activities.index'					=> 93,
							'hr.report.activities.show'						=> 93,
							'hr.report.activities.create'					=> 94,
							'hr.report.activities.store'					=> 94,
							'hr.report.activities.edit'						=> 95,
							'hr.report.activities.update'					=> 95,
							'hr.report.activities.delete'					=> 96,

							'hr.report.attendances.index'					=> 97,
							'hr.report.attendances.show'					=> 97,
							'hr.report.attendances.create'					=> 98,
							'hr.report.attendances.store'					=> 98,
							'hr.report.attendances.edit'					=> 99,
							'hr.report.attendances.update'					=> 99,
							'hr.report.attendances.delete'					=> 100,
							
							'hr.activity.logs.index'						=> 97,
							'hr.activity.logs.show'							=> 97,
							'hr.activity.logs.create'						=> 98,
							'hr.activity.logs.store'						=> 98,
							'hr.activity.logs.edit'							=> 99,
							'hr.activity.logs.update'						=> 99,
							'hr.activity.logs.delete'						=> 100,
							
							'hr.password.get'								=> 101,
							'hr.password.post'								=> 101,
							'hr.logout.get'									=> 1,

							'hr.infomessage.index'							=> 49,

							'hr.recordlogs.index'							=> 102,
							'hr.recordlogs.show'							=> 102,
							'hr.recordlogs.create'							=> 103,
							'hr.recordlogs.store'							=> 103,
							'hr.recordlogs.edit'							=> 104,
							'hr.recordlogs.update'							=> 104,
							'hr.recordlogs.delete'							=> 105,

							'hr.queue.index'								=> 101,

							'hr.person.widgets.index' 						=> 101,
							'hr.person.widgets.show'						=> 101,
							'hr.person.widgets.create'						=> 101,
							'hr.person.widgets.store'						=> 101,
							'hr.person.widgets.edit'						=> 101,
							'hr.person.widgets.update'						=> 101,
							'hr.person.widgets.delete'						=> 101,

							'hr.ipwhitelists.index' 						=> 49,
							'hr.ipwhitelists.show'							=> 49,
							'hr.ipwhitelists.create'						=> 49,
							'hr.ipwhitelists.store'							=> 49,
							'hr.ipwhitelists.edit'							=> 49,
							'hr.ipwhitelists.update'						=> 49,
							'hr.ipwhitelists.delete'						=> 49,
				];
			return $routes_acl;
		});

		// If you use this line of code then it'll be available in any view
		// as $site_settings but you may also use app('site_settings') as well
		View::share('hr_acl', app('hr_acl'));
	}

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		Config::set('current.absence.version', '1.4');
		Config::set('current.absence.url1', 'https://rcmsystem.co/AbsentSystem.exe');
		Config::set('current.absence.url2', 'https://rcmsystem.co/Config.exe');

		parent::boot($router);

		// Customize filter
		Route::filter('hr_acl', function()
		{
			if (!Session::has('loggedUser'))
			{
				if (Request::ajax())
				{
					return Response::make('Unauthorized', 401);
				}
				else
				{
					return Redirect::guest(route('hr.login.get'));
				}
			}
			else
			{
				$total_orgs 									= $this->dispatch(new Getting(new Organisation, [], [],1, 100));
				$count_orgs 									= json_decode($total_orgs);
				
				if(!$count_orgs->meta->success)
				{
					App::abort(404);
				}

				if($count_orgs->pagination->total_data>0)
				{
					//check user logged in 
					$results 									= $this->dispatch(new Getting(new Person, ['id' => Session::get('loggedUser'), 'withattributes' => ['organisation'], 'checkwork' => true], ['created_at' => 'asc'],1, 1));

					$contents 									= json_decode($results);

					if(!$contents->meta->success)
					{
						Session::flush();
						return Redirect::guest(route('hr.login.get'));
					}

					Session::put('user.id', $contents->data->id);
					Session::put('user.name', $contents->data->name);
					Session::put('user.username', $contents->data->username);
					Session::put('user.gender', $contents->data->gender);
					Session::put('user.avatar', $contents->data->avatar);
					Session::put('user.lupassword', $contents->data->last_password_updated_at);

					$results 									= $this->dispatch(new Getting(new Work, ['personid' => Session::get('loggedUser'), 'active' => true, 'withattributes' => ['workauthentications', 'workauthentications.organisation', 'chart']], ['end' => 'asc'],1, 100));

					$contents_2 								= json_decode($results);

					if(!$contents_2->meta->success || !count($contents_2->data))
					{
						Session::flush();
						return Redirect::guest(route('hr.login.get'));
					}

					if(!Session::has('user.organisationid'))
					{
						Session::put('user.organisationid', $contents->data->organisation->id);
						Session::put('user.chartname', $contents_2->data[0]->chart->name);
						Session::put('user.chartpath', $contents_2->data[0]->chart->path.',');
						Session::put('user.workid', $contents_2->data[0]->id);
						Session::put('user.branchid', $contents_2->data[0]->chart->branch_id);
						Session::put('user.organisationname', $contents->data->organisation->name);
					}

					$workid										= [];
					$chartids									= [];
					$chartsids									= [];
					$chartnames									= [];
					$organisationids							= [];
					$organisationnames							= [];
					$organisationcodes							= [];
					
					foreach ($contents_2->data as $key => $value) 
					{
						$workid[]								= $value->id;
						foreach ($value->workauthentications as $key_2 => $value_2) 
						{
							if(!isset($chartids[$value_2->organisation->id]) || !in_array($value->chart->id, $chartids[$value_2->organisation->id]))
							{
								$chartids[$value_2->organisation->id][]			= $value->chart->id;
								$chartsids[]									= $value->chart->id;
							}

							if(!isset($chartnames[$value_2->organisation->id]) || !in_array($value->chart->name, $chartnames[$value_2->organisation->id]))
							{
								$chartnames[$value_2->organisation->id][]		= $value->chart->name;
							}

							if(!in_array($value_2->organisation->name, $organisationnames))
							{
								$organisationnames[]							= $value_2->organisation->name;
							}

							if(!in_array($value_2->organisation->id, $organisationids))
							{
								$organisationids[]								= $value_2->organisation->id;
							}

							if(!in_array($value_2->organisation->code, $organisationcodes))
							{
								$organisationcodes[]							= $value_2->organisation->code;
							}
						}
					}

					Session::put('user.organisationids', $organisationids);
					Session::put('user.organisationnames', $organisationnames);
					Session::put('user.organisationcodes', $organisationcodes);
					Session::put('user.chartnames', $chartnames);

					if(Input::has('org_id') && Input::get('org_id')!=0)
					{
						$orgids 									= [Input::get('org_id')];
					}
					else
					{
						$orgids 									= $organisationids;
					}

					//check access
					$menu 											= app('hr_acl')[Route::currentRouteName()];

					$results 										= $this->dispatch(new Getting(new WorkAuthentication, ['menuid' => $menu, 'workid' => $workid, 'organisationid' => $orgids], ['tmp_auth_group_id' => 'asc'],1, 1));

					$contents 										= json_decode($results);

					if((!$contents->meta->success))
					{
						Session::flush();
						return Redirect::guest(route('hr.login.get'));
					}


					$results 									= $this->dispatch(new Getting(new Policy, ['type' => 'passwordreminder' ,'organisationid' => Session::get('user.organisationid'), 'ondate' => date('Y-m-d H:i:s')], ['created_at' => 'desc'],1, 1));

					$contents_3 								= json_decode($results);

					if(!$contents_3->meta->success)
					{
						Session::flush();
						return Redirect::guest(route('hr.login.get'));
					}

					if(date('Y-m-d H:i:s', strtotime(Session::get('user.lupassword'))) < date('Y-m-d H:i:s', strtotime($contents_3->data->value)))
					{
						Session::put('alert_info', 'Sudah waktunya mengganti password anda');
					}

					Session::put('user.menuid', $contents->data->tmp_auth_group_id);
				}
				elseif(Route::currentRouteName()=='hr.logout.get')
				{
					Session::flush();
					return Redirect::guest(route('hr.login.get'));
				}
				elseif(Route::currentRouteName()=='hr.organisations.store')
				{
					//
				}
				elseif(Route::currentRouteName()!='hr.organisations.create')
				{
					$results 										= $this->dispatch(new Getting(new Person, ['id' => Session::get('loggedUser'), 'defaultemail' => true], ['created_at' => 'asc'],1, 1));

					$contents 										= json_decode($results);

					if(!$contents->meta->success)
					{
						Session::flush();
						return Redirect::guest(route('hr.login.get'));
					}

					Session::put('user.id', $contents->data->id);
					Session::put('user.name', $contents->data->name);
					Session::put('user.email', $contents->data->contacts[0]->value);
					Session::put('user.gender', $contents->data->gender);
					Session::put('user.avatar', $contents->data->avatar);

					return Redirect::route('hr.organisations.create');
				}

			}
		});
	}
		
	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
