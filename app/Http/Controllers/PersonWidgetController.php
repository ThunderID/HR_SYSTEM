<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Person;
use App\Models\PersonWidget;

class PersonWidgetController extends BaseController 
{

	protected $controller_name 					= 'personwidget';

	public function index()
	{		
		$this->layout->page 					= view('pages.personwidgets.index');

		return $this->layout;
	}

	public function create($id = null)
	{
		if(is_null($id))
		{
			$data 								= null;
			$this->layout->page 				= view('pages.personwidgets.create', compact('id', 'data'));
		}
		else
		{
			$result								= $this->dispatch(new Getting(new PersonWidget, ['ID' => $id], ['created_at' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}
		
			$data 				 				= json_decode(json_encode($content->data), true);
			$this->layout->page 				= view('pages.personwidgets.create', compact('id', 'data'));
		}

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		if (Input::has('org_id'))
		{
			$org_id								= Input::get('org_id');
		}
		else 
		{
			$org_id	 							= Session::get('user.organisationid');
		}

		$attributes['organisation_id']			= $org_id;
		$attributes['type']						= Input::get('type');
		$attributes['dashboard']				= Input::get('dashboard');
		$attributes['is_active']				= Input::get('is_active');
		$attributes['widget']					= Input::get('widget_template');
		$attributes['person_id']				= Session::get('loggedUser');
		$attributes['row']						= 1;
		$attributes['col']						= 1;

		/*=====WIDGET DASHBOARD ORGANISATION=====*/
		if ($attributes['dashboard'] == 'organisation')
		{
			/*====DASHBOARD ORGANISATION TYPE STATIC====*/
			if ($attributes['type'] == 'stat')
			{
				/*====CHECK HAVE DASHBOARD IN ORGANISATION OR PERSON====*/
				if ($attributes['dashboard'] == 'organisation')
				{
					$result									= $this->dispatch(new Getting(new PersonWidget, ['type' => 'stat', 'dashboard' => 'organisation'], [] ,1, 100));
				}
				else
				{
					$result									= $this->dispatch(new Getting(new PersonWidget, ['type' => 'stat', 'dashboard' => 'person'], [] ,1, 100));

				}

				$content 									= json_decode($result);

				if (!$content->meta->success)
				{
					App::abort(404);
				}

				$data 										= json_decode(json_encode($content->data), true);

				if (count($data)<=4) 
				{
					/*====QUERY PERSON STATUS STATIC====*/
					if (Input::get('widget_query') == 'state') 
					{
						$query 	= ['widget_template' 	=> 'plain', 
									'widget_title' 		=> Input::get('title'),
									'widget_options'	=> [	'widgetlist'	=> 
																[
																	'title' 				=> Input::get('widget_option_title'),
																	'organisation_id'		=> $org_id,
																	'search'				=> [Input::get('widget_data') => Input::get('periode'), 'status' => Input::get('status')],
																	'sort'					=> [],
																	'page'					=> 1,
																	'per_page'				=> 1
																]
															] 
								];
					}
					/*====QUERY PERSON WORKLEAVE STATIC====*/
					else if (Input::get('widget_query') == 'workleave') 
					{
						$query 	= ['widget_template' 	=> 'plain', 
									'widget_title' 		=> Input::get('title'),
									'widget_options'	=> [	'widgetlist'	=> 
																[
																	'title' 				=> Input::get('widget_option_title'),
																	'organisation_id'		=> $org_id,
																	'search'				=> [Input::get('widget_data') => Input::get('periode'), 'quota' => false],
																	'sort'					=> [],
																	'page'					=> 1,
																	'per_page'				=> 1
																]
															] 
								];
					}
					/*====QUERY BRANCH & DOCUMENT STATIC====*/
					else if ((Input::get('widget_query') == 'branch')||(Input::get('widget_query') == 'document'))
					{
						$query 	= ['widget_template'		=> 'plain',
									'widget_title'			=> Input::get('title'),
									'widget_options'		=> 	[
																	'widgetlist'		=>
																	[
																		'title' 			=> Input::get('widget_option_title'),
																		'organisation_id'	=> $org_id,
																		'search'			=> [],
																		'sort'				=> [],
																		'page'				=> 1,
																		'per_page'			=> 100,
																	]
																]
									];
					}
					/*====QUERY PERSON STATIC====*/
					else if (Input::get('widget_query') == 'person')
					{
						$query 	= ['widget_template'		=> 'plain',
									'widget_title'			=> Input::get('title'),
									'widget_options'		=> 	[
																	'widgetlist'		=>
																	[
																		'title' 			=> Input::get('widget_option_title'),
																		'organisation_id'	=> $org_id,
																		'search'			=> [Input::get('widget_data') => true],
																		'sort'				=> [],
																		'page'				=> 1,
																		'per_page'			=> 100,
																	]
																]
									];
					}
					/*====QUERY TIME LOSS RATE STATIC====*/
					else if(Input::get('widget_query') == 'lossrate')
					{
						$query 	= ['widget_template'		=> 'plain',
									'widget_title'			=> Input::get('title'),
									'widget_options'		=> 	[
																	'widgetlist'		=>
																	[
																		'title' 			=> Input::get('widget_option_title'),
																		'organisation_id'	=> $org_id,
																		'search'			=> [Input::get('widget_data') => ['organisationid' => $org_id, 'on' => [Input::get('periode'), Input::get('periode')]]],
																		'sort'				=> [],
																		'page'				=> 1,
																		'per_page'			=> 100,
																	]
																]
									];
					}
					/*====QUERY IDLE STATIC====*/
					else
					{
						$query 	= ['widget_template' 	=> 'plain', 
									'widget_title' 		=> Input::get('title'),
									'widget_options'	=> [	'widgetlist'	=> 
																[
																	'title' 				=> Input::get('widget_option_title'),
																	'organisation_id'		=> Input::get('org_id'),
																	'search'				=> [Input::get('widget_data') => Input::get('periode')],
																	'sort'					=> [],
																	'page'					=> 1,
																	'per_page'				=> 1
																]
															] 
								];
					}
				}
				else
				{
					return Redirect::back()->withErrors(['Maaf untuk widget type stat, tidak bisa lebih dari 4 widget']);
				}
			}
			/*====DASHBOARD ORGANISATION TYPE TABLE====*/
			else 
			{
				/*====QUERY PERSON SP TABLE====*/
				if (Input::get('widget_query') == 'sp') 
				{
					$query	= [	'widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> 	[	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'notpersondocumentID' => 0],
																'sort'					=> ['created_at' => 'asc'],
																'page'					=> 1,
																'per_page'				=> 15
															]
														] 
							];
				}
				/*====QUERY PERSON WORKLEAVE TABLE====*/
				else if (Input::get('widget_query') == 'workleave') 
				{
					$query 	= ['widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'quota' => false, 'withattributes' => ['person']],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 15
															]
														] 
							];
				}
				/*====QUERY PERSON TABLE====*/
				else if (Input::get('widget_query') == 'person')
				{
					$query 	= ['widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'withattributes' => ['works.branch']],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 10
															]
														] 
							];
				}
				else
				{
					$query 	= ['widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> Input::get('org_id'),
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'withattributes' => ['processlog.person']],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 15
															]
														] 
							];
				}
			}
		}
		/*====WIDGET DASHBOARD PERSON====*/
		else
		{
			/*====QUERY PERSON STATUS====*/
			if ($attributes['type'] == 'stat')
			{
				if (Input::get('widget_query') == 'leftquota')
				{
					$query 	= ['widget_template'	=> 'plain',
								'widget_title'		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title'					=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> ['id' => Session::get('loggedUser'), Input::get('widget_data') => ['organisationid' => $org_id, 'on' => Input::get('periode')]],
																'sort'					=> ['persons.name' => 'asc'],
																'page'					=> 1,
																'per_page'				=> 1
															]
														]
							];
				}
				else if (Input::get('widget_query') == 'lossrate')
				{
					$query 	= ['widget_template' 	=> 'plain', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> ['id' => Session::get('loggedUser'), Input::get('widget_data') => ['organisationid' => $org_id, 'on' => [Input::get('periode'), Input::get('periode')]]],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 100
															]
														] 
							];
				}
				else
				{
					$query 	= ['widget_template' 	=> 'plain', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> Input::get('org_id'),
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'personid' => Session::get('loggedUser')],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 1
															]
														] 
							];
				}
			}
			else 
			{
				if (Input::get('widget_query') == 'work')
				{
					$query 	= ['widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> $org_id,
																'search'				=> ['personid' => Session::get('loggedUser'), 'withattributes' => ['chart', 'chart.branch', 'chart.branch.organisation']],
																'sort'					=> ['end' => 'asc'],
																'page'					=> 1,
																'per_page'				=> 10
															]
														] 
							];
				}
				else 
				{
					$query 	= ['widget_template' 	=> 'panel', 
								'widget_title' 		=> Input::get('title'),
								'widget_options'	=> [	'widgetlist'	=> 
															[
																'title' 				=> Input::get('widget_option_title'),
																'organisation_id'		=> Input::get('org_id'),
																'search'				=> [Input::get('widget_data') => Input::get('periode'), 'personid' => Session::get('loggedUser')],
																'sort'					=> [],
																'page'					=> 1,
																'per_page'				=> 15
															]
														] 
							];
				}
			}
		}

		$attributes['query']					= json_encode($query);

		// dd($attributes);exit;
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new PersonWidget, $attributes, $id, new Person, Session::get('loggedUser')));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('PersonWidget', $value2);
					}
				}
				else
				{
					$errors->add('PersonWidget', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();

			if ($attributes['dashboard'] == 'organisation')
			{
				return Redirect::route('hr.organisations.show', [$org_id, 'org_id' => $org_id])->with('local_msg', $errors)->with('alert_success', 'Widget sudah disimpan');			
			}
			else
			{
				return Redirect::route('hr.persons.show', [Session::get('loggedUser'), 'org_id' => $org_id])->with('local_msg', $errors)->with('alert_success', 'Widget sudah disimpan');				
			}
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
	{
		$result								= $this->dispatch(new Getting(new PersonWidget, ['ID' => $id], ['created_at' => 'asc'] ,1, 1));

		$content 							= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		$data 								= json_decode(json_encode($content->data), true);

		$this->layout->page 				= view('pages.personwidgets.show', compact('data', 'id'));

		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function destroy($id)
	{
		$attributes 						= ['username' => Session::get('user.username'), 'password' => Input::get('password')];

		$results 							= $this->dispatch(new Checking(new Person, $attributes));

		$content 							= json_decode($results);

		if($content->meta->success)
		{
			$results 						= $this->dispatch(new Deleting(new PersonWidget, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				$contents					= json_decode(json_encode($contents->data), true);
				$data 						= json_decode($contents['query']);

				if ($contents['dashboard']=='organisation')
				{
					return Redirect::route('hr.organisations.show', [$contents['organisation_id'], 'org_id' => $contents['organisation_id']])->with('alert_success', 'Widget sudah dihapus');	
				}
				else
				{
					return Redirect::route('hr.persons.show', [$contents['person_id'], 'org_id' => $contents['organisation_id']])->with('alert_success', 'Widget sudah dihapus');
				}
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}
