<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Config, Session, App, View, Route, Redirect, Request;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Models\Authentication;
use App\Models\Person;

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
							'hr.organisations.index'						=> [['1','2','3','4'], 'read'],
							'hr.organisations.show'							=> [['1','2','3','4'], 'read'],
							'hr.organisations.create'						=> [['1'], 'create'],
							'hr.organisations.store'						=> [['1'], 'create'],
							'hr.organisations.edit'							=> [['1'], 'update'],
							'hr.organisations.update'						=> [['1'], 'update'],
							'hr.organisations.delete'						=> [['1'], 'delete'],

							'hr.idles.index'								=> [['1','2','3','4'], 'read'],
							'hr.idles.show'									=> [['1','2','3','4'], 'read'],
							'hr.idles.create'								=> [['1'], 'create'],
							'hr.idles.store'								=> [['1'], 'create'],
							'hr.idles.edit'									=> [['1'], 'update'],
							'hr.idles.update'								=> [['1'], 'update'],
							'hr.idles.delete'								=> [['1'], 'delete'],

							'hr.branches.index'								=> [['1','2','3','4'], 'read'],
							'hr.branches.show'								=> [['1','2','3','4'], 'read'],
							'hr.branches.create'							=> [['1'], 'create'],
							'hr.branches.store'								=> [['1'], 'create'],
							'hr.branches.edit'								=> [['1'], 'update'],
							'hr.branches.update'							=> [['1'], 'update'],
							'hr.branches.delete'							=> [['1'], 'delete'],

							'hr.branch.contacts.index'						=> [['1','2','3','4'], 'read'],
							'hr.branch.contacts.show'						=> [['1','2','3','4'], 'read'],
							'hr.branch.contacts.create'						=> [['1'], 'create'],
							'hr.branch.contacts.store'						=> [['1'], 'create'],
							'hr.branch.contacts.edit'						=> [['1'], 'update'],
							'hr.branch.contacts.update'						=> [['1'], 'update'],
							'hr.branch.contacts.delete'						=> [['1'], 'delete'],

							'hr.branch.charts.index'						=> [['1','2','3','4'], 'read'],
							'hr.branch.charts.show'							=> [['1','2','3','4'], 'read'],
							'hr.branch.charts.create'						=> [['1'], 'create'],
							'hr.branch.charts.store'						=> [['1'], 'create'],
							'hr.branch.charts.edit'							=> [['1'], 'update'],
							'hr.branch.charts.update'						=> [['1'], 'update'],
							'hr.branch.charts.delete'						=> [['1'], 'delete'],

							'hr.branch.apis.index'							=> [['1','2','3','4'], 'read'],
							'hr.branch.apis.show'							=> [['1','2','3','4'], 'read'],
							'hr.branch.apis.create'							=> [['1'], 'create'],
							'hr.branch.apis.store'							=> [['1'], 'create'],
							'hr.branch.apis.edit'							=> [['1'], 'update'],
							'hr.branch.apis.update'							=> [['1'], 'update'],
							'hr.branch.apis.delete'							=> [['1'], 'delete'],

							'hr.branch.fingers.index'						=> [['1','2','3'], 'read'],
							'hr.branch.fingers.show'						=> [['1','2','3'], 'read'],
							'hr.branch.fingers.create'						=> [['1','2'], 'create'],
							'hr.branch.fingers.store'						=> [['1','2'], 'create'],
							'hr.branch.fingers.edit'						=> [['1','2'], 'update'],
							'hr.branch.fingers.update'						=> [['1','2'], 'update'],
							'hr.branch.fingers.delete'						=> [['1','2'], 'delete'],

							'hr.chart.authentications.index'				=> [['1','2','3','4'], 'read'],
							'hr.chart.authentications.show'					=> [['1','2','3','4'], 'read'],
							'hr.chart.authentications.create'				=> [['1'], 'create'],
							'hr.chart.authentications.store'				=> [['1'], 'create'],
							'hr.chart.authentications.edit'					=> [['1'], 'update'],
							'hr.chart.authentications.update'				=> [['1'], 'update'],
							'hr.chart.authentications.delete'				=> [['1'], 'delete'],

							'hr.chart.calendars.index'						=> [['1','2','3'], 'read'],
							'hr.chart.calendars.show'						=> [['1','2','3'], 'read'],
							'hr.chart.calendars.create'						=> [['1','2'], 'create'],
							'hr.chart.calendars.store'						=> [['1','2'], 'create'],
							'hr.chart.calendars.edit'						=> [['1','2'], 'update'],
							'hr.chart.calendars.update'						=> [['1','2'], 'update'],
							'hr.chart.calendars.delete'						=> [['1','2'], 'delete'],

							'hr.chart.calendars.index'						=> [['1','2','3'], 'read'],
							'hr.chart.calendars.show'						=> [['1','2','3'], 'read'],
							'hr.chart.calendars.create'						=> [['1','2'], 'create'],
							'hr.chart.calendars.store'						=> [['1','2'], 'create'],
							'hr.chart.calendars.edit'						=> [['1','2'], 'update'],
							'hr.chart.calendars.update'						=> [['1','2'], 'update'],
							'hr.chart.calendars.delete'						=> [['1','2'], 'delete'],

							'hr.calendars.index'							=> [['1','2','3'], 'read'],
							'hr.calendars.show'								=> [['1','2','3'], 'read'],
							'hr.calendars.create'							=> [['1','2'], 'create'],
							'hr.calendars.store'							=> [['1','2'], 'create'],
							'hr.calendars.edit'								=> [['1','2'], 'update'],
							'hr.calendars.update'							=> [['1','2'], 'update'],
							'hr.calendars.delete'							=> [['1','2'], 'delete'],

							'hr.calendar.schedules.index'					=> [['1','2','3'], 'read'],
							'hr.calendar.schedules.show'					=> [['1','2','3'], 'read'],
							'hr.calendar.schedules.create'					=> [['1','2'], 'create'],
							'hr.calendar.schedules.store'					=> [['1','2'], 'create'],
							'hr.calendar.schedules.edit'					=> [['1','2'], 'update'],
							'hr.calendar.schedules.update'					=> [['1','2'], 'update'],
							'hr.calendar.schedules.delete'					=> [['1','2'], 'delete'],

							'hr.calendar.charts.index'						=> [['1','2','3'], 'read'],
							'hr.calendar.charts.show'						=> [['1','2','3'], 'read'],
							'hr.calendar.charts.create'						=> [['1','2'], 'create'],
							'hr.calendar.charts.store'						=> [['1','2'], 'create'],
							'hr.calendar.charts.edit'						=> [['1','2'], 'update'],
							'hr.calendar.charts.update'						=> [['1','2'], 'update'],
							'hr.calendar.charts.delete'						=> [['1','2'], 'delete'],

							'hr.workleaves.index'							=> [['1','2','3'], 'read'],
							'hr.workleaves.show'							=> [['1','2','3'], 'read'],
							'hr.workleaves.create'							=> [['1','2'], 'create'],
							'hr.workleaves.store'							=> [['1','2'], 'create'],
							'hr.workleaves.edit'							=> [['1','2'], 'update'],
							'hr.workleaves.update'							=> [['1','2'], 'update'],
							'hr.workleaves.delete'							=> [['1','2'], 'delete'],

							'hr.documents.index'							=> [['1','2','3'], 'read'],
							'hr.documents.show'								=> [['1','2','3'], 'read'],
							'hr.documents.create'							=> [['1','2'], 'create'],
							'hr.documents.store'							=> [['1','2'], 'create'],
							'hr.documents.edit'								=> [['1','2'], 'update'],
							'hr.documents.update'							=> [['1','2'], 'update'],
							'hr.documents.delete'							=> [['1','2'], 'delete'],

							'hr.document.templates.index'					=> [['1','2','3'], 'read'],
							'hr.document.templates.show'					=> [['1','2','3'], 'read'],
							'hr.document.templates.create'					=> [['1','2'], 'create'],
							'hr.document.templates.store'					=> [['1','2'], 'create'],
							'hr.document.templates.edit'					=> [['1','2'], 'update'],
							'hr.document.templates.update'					=> [['1','2'], 'update'],
							'hr.document.templates.delete'					=> [['1','2'], 'delete'],

							'hr.persons.index'								=> [['1','2','3','4'], 'read'],
							'hr.persons.show'								=> [['1','2','3','4'], 'read'],
							'hr.persons.create'								=> [['1','2','3','4'], 'create'],
							'hr.persons.store'								=> [['1','2','3','4'], 'create'],
							'hr.persons.edit'								=> [['1','2','3','4'], 'update'],
							'hr.persons.update'								=> [['1','2','3','4'], 'update'],
							'hr.persons.delete'								=> [['1','2','3','4'], 'delete'],
				
							'hr.person.contacts.index'						=> [['1','2','3','4'], 'read'],
							'hr.person.contacts.show'						=> [['1','2','3','4'], 'read'],
							'hr.person.contacts.create'						=> [['1','2','3','4'], 'create'],
							'hr.person.contacts.store'						=> [['1','2','3','4'], 'create'],
							'hr.person.contacts.edit'						=> [['1','2','3','4'], 'update'],
							'hr.person.contacts.update'						=> [['1','2','3','4'], 'update'],
							'hr.person.contacts.delete'						=> [['1','2','3','4'], 'delete'],
				
							'hr.person.relatives.index'						=> [['1','2','3','4'], 'read'],
							'hr.person.relatives.show'						=> [['1','2','3','4'], 'read'],
							'hr.person.relatives.create'					=> [['1','2','3','4'], 'create'],
							'hr.person.relatives.store'						=> [['1','2','3','4'], 'create'],
							'hr.person.relatives.edit'						=> [['1','2','3','4'], 'update'],
							'hr.person.relatives.update'					=> [['1','2','3','4'], 'update'],
							'hr.person.relatives.delete'					=> [['1','2','3','4'], 'delete'],

							'hr.person.works.index'							=> [['1','2','3','4'], 'read'],
							'hr.person.works.show'							=> [['1','2','3','4'], 'read'],
							'hr.person.works.create'						=> [['1','2','3','4'], 'create'],
							'hr.person.works.store'							=> [['1','2','3','4'], 'create'],
							'hr.person.works.edit'							=> [['1','2','3','4'], 'update'],
							'hr.person.works.update'						=> [['1','2','3','4'], 'update'],
							'hr.person.works.delete'						=> [['1','2','3','4'], 'delete'],

							'hr.person.schedules.index'						=> [['1','2','3','4'], 'read'],
							'hr.person.schedules.show'						=> [['1','2','3','4'], 'read'],
							'hr.person.schedules.create'					=> [['1','2','3','4'], 'create'],
							'hr.person.schedules.store'						=> [['1','2','3','4'], 'create'],
							'hr.person.schedules.edit'						=> [['1','2','3','4'], 'update'],
							'hr.person.schedules.update'					=> [['1','2','3','4'], 'update'],
							'hr.person.schedules.delete'					=> [['1','2','3','4'], 'delete'],
							
							'hr.person.schedule.ajax'						=> [['1','2','3','4'], 'read'],

							'hr.person.workleaves.index'					=> [['1','2','3','4'], 'read'],
							'hr.person.workleaves.show'						=> [['1','2','3','4'], 'read'],
							'hr.person.workleaves.create'					=> [['1','2','3','4'], 'create'],
							'hr.person.workleaves.store'					=> [['1','2','3','4'], 'create'],
							'hr.person.workleaves.edit'						=> [['1','2','3','4'], 'update'],
							'hr.person.workleaves.update'					=> [['1','2','3','4'], 'update'],
							'hr.person.workleaves.delete'					=> [['1','2','3','4'], 'delete'],

							'hr.person.documents.index'						=> [['1','2','3','4'], 'read'],
							'hr.person.documents.show'						=> [['1','2','3','4'], 'read'],
							'hr.person.documents.create'					=> [['1','2','3','4'], 'create'],
							'hr.person.documents.store'						=> [['1','2','3','4'], 'create'],
							'hr.person.documents.edit'						=> [['1','2','3','4'], 'update'],
							'hr.person.documents.update'					=> [['1','2','3','4'], 'update'],
							'hr.person.documents.delete'					=> [['1','2','3','4'], 'delete'],

							'hr.report.attendances.index'					=> [['1','2','3','4'], 'read'],
							'hr.report.attendances.show'					=> [['1','2','3','4'], 'read'],
							'hr.report.attendances.create'					=> [['1','2','3','4'], 'create'],
							'hr.report.attendances.store'					=> [['1','2','3','4'], 'create'],
							'hr.report.attendances.edit'					=> [['1','2','3','4'], 'update'],
							'hr.report.attendances.update'					=> [['1','2','3','4'], 'update'],
							'hr.report.attendances.delete'					=> [['1','2','3','4'], 'delete'],

							'hr.attendance.persons.index'					=> [['1','2','3','4'], 'read'],
							'hr.attendance.persons.show'					=> [['1','2','3','4'], 'read'],
							'hr.attendance.persons.create'					=> [['1','2','3','4'], 'create'],
							'hr.attendance.persons.store'					=> [['1','2','3','4'], 'create'],
							'hr.attendance.persons.edit'					=> [['1','2','3','4'], 'update'],
							'hr.attendance.persons.update'					=> [['1','2','3','4'], 'update'],
							'hr.attendance.persons.delete'					=> [['1','2','3','4'], 'delete'],

							'hr.report.wages.index'							=> [['1','2','3','4'], 'read'],
							'hr.report.wages.show'							=> [['1','2','3','4'], 'read'],
							'hr.report.wages.create'						=> [['1','2','3','4'], 'create'],
							'hr.report.wages.store'							=> [['1','2','3','4'], 'create'],
							'hr.report.wages.edit'							=> [['1','2','3','4'], 'update'],
							'hr.report.wages.update'						=> [['1','2','3','4'], 'update'],
							'hr.report.wages.delete'						=> [['1','2','3','4'], 'delete'],
							
							'hr.password.get'								=> [['1','2','3','4'], 'delete'],
							'hr.password.post'								=> [['1','2','3','4'], 'delete'],
							'hr.logout.get'									=> [['1','2','3','4'], 'delete'],
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
				//check user logged in 
				$results 									= $this->dispatch(new Getting(new Person, ['id' => Session::get('loggedUser'), 'CurrentWork' => true, 'withattributes' => ['works.branch', 'works.branch.organisation'], 'defaultemail' => true], ['created_at' => 'asc'],1, 1));

				$contents 									= json_decode($results);

				if(!$contents->meta->success || !count($contents->data->works))
				{
					App::abort(404);
				}

				if(!Session::has('user.organisationid'))
				{
					Session::put('user.organisationid', $contents->data->works[0]->branch->organisation->id);
					Session::put('user.chartname', $contents->data->works[0]->name);
				}

				$chartids									= [];
				$chartnames									= [];
				$organisationids							= [];
				$organisationnames							= [];
				
				foreach ($contents->data->works as $key => $value) 
				{
					if(!in_array($value->id, $chartids))
					{
						$chartids[]							= $value->id;
					}

					if(!in_array($value->name, $chartnames))
					{
						$chartnames[]						= $value->name;
					}

					if(!in_array($value->branch->organisation->name, $organisationnames))
					{
						$organisationnames[]				= $value->branch->organisation->name;
					}

					if(!in_array($value->branch->organisation->id, $organisationids))
					{
						$organisationids[]					= $value->branch->organisation->id;
					}
				}

				Session::put('user.organisationids', $organisationids);
				Session::put('user.organisationnames', $organisationnames);
				Session::put('user.chartnames', $chartnames);

				Session::put('user.chartname', $contents->data->works[0]->name);
				Session::put('user.organisationname', $contents->data->works[0]->branch->organisation->name);

				Session::put('user.id', $contents->data->id);
				Session::put('user.name', $contents->data->name);
				Session::put('user.email', $contents->data->contacts[0]->value);
				Session::put('user.gender', $contents->data->gender);
				Session::put('user.avatar', $contents->data->avatar);
				
				//check access
				$menu 											= app('hr_acl')[Route::currentRouteName()];

				$results 										= $this->dispatch(new Getting(new Authentication, ['menuid' => $menu[0], 'chartid' => $chartids, 'access' => $menu[1]], ['chart_id' => 'asc'],1, 1));

				$contents 										= json_decode($results);

				if(!$contents->meta->success)
				{
					Session::flush();
					return Redirect::guest(route('hr.login.get'));
				}

				if(in_array(strtolower($menu[1]), ['create','update','delete']))
				{
					Session::put('allow.filter', false);
				}
				else
				{
					Session::put('allow.filter', true);
				}

				Session::put('user.menuid', $contents->data->menu_id);
				Session::put('user.chartid', $contents->data->chart_id);
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
