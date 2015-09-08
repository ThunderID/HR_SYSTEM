<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
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

		$attributes['type']						= Input::get('type');
		$attributes['dashboard']				= Input::get('dashboard');
		$attributes['is_active']				= Input::get('is_active');
		$attributes['widget']					= Input::get('widget_template');
		$attributes['person_id']				= Session::get('loggedUser');
		$attributes['row']						= 1;
		$attributes['col']						= 1;


		$result									= $this->dispatch(new Getting(new PersonWidget, ['type' => 'stat'], [] ,1, 100));
		$content 								= json_decode($result);

		if (!$content->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($content->data), true);
		
		if (count($data)<=3) 
		{

			if (Input::get('widget_query') == 'sp') 
			{
				$query 									= ['widget_template' 	=> 'plain', 
															'widget_title' 		=> Input::get('title'),
															'widget_options'	=> [	'widgetlist'	=> 
																						[
																							'title' 				=> Input::get('widget_option_title'),
																							'organisation_id'		=> $org_id,
																							'search'				=> [Input::get('widget_data') => Input::get('periode'), ['notpersondocumentID' == 0]],
																							'sort'					=> ['created_at' => 'asc'],
																							'page'					=> 1,
																							'per_page'				=> 100
																						]
																					] 
														];
			}
			else if (Input::get('widget_query') == 'state') 
			{
				$query 									= ['widget_template' 	=> 'plain_no_title', 
															'widget_title' 		=> Input::get('title'),
															'widget_options'	=> [	'widgetlist'	=> 
																						[
																							'title' 				=> Input::get('widget_option_title'),
																							'organisation_id'		=> $org_id,
																							'search'				=> [Input::get('widget_data') => Input::get('periode'), 'status' => Input::get('status')],
																							'sort'					=> [],
																							'page'					=> 1,
																							'per_page'				=> 100
																						]
																					] 
														];
			}
			else if (Input::get('widget_query') == 'workleave') 
			{
				$query 									= ['widget_template' 	=> 'plain_no_title', 
															'widget_title' 		=> Input::get('title'),
															'widget_options'	=> [	'widgetlist'	=> 
																						[
																							'title' 				=> Input::get('widget_option_title'),
																							'organisation_id'		=> $org_id,
																							'search'				=> [Input::get('widget_data') => Input::get('periode'), 'quota' => false],
																							'sort'					=> [],
																							'page'					=> 1,
																							'per_page'				=> 100
																						]
																					] 
														];
			}
			else
			{
				$query 									= ['widget_template' 	=> 'plain', 
															'widget_title' 		=> Input::get('title'),
															'widget_options'	=> [	'widgetlist'	=> 
																						[
																							'title' 				=> Input::get('widget_option_title'),
																							'organisation_id'		=> Input::get('org_id'),
																							'search'				=> [Input::get('widget_data') => Input::get('periode')],
																							'sort'					=> [],
																							'page'					=> 1,
																							'per_page'				=> 100
																						]
																					] 
														];
			}


			$attributes['query']					= json_encode($query);

			// dd($attributes);exit;
			
			$errors 								= new MessageBag();
			
			DB::beginTransaction();

			$content 								= $this->dispatch(new Saving(new PersonWidget, $attributes, $id));

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

		}
		else
		{
			return Redirect::back()->withErrors(['Maaf untuk widget type stat, tidak bisa lebih dari 4 widget']);
		}

		if(!$errors->count())
		{
			DB::commit();

			return Redirect::route('hr.organisations.show', [$org_id, 'org_id' => $org_id])->with('local_msg', $errors)->with('alert_success', 'Widget sudah disimpan');
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
				return Redirect::route('hr.personwidgets.index')->with('alert_success', 'Widget "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}
