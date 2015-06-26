<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Organisation;
use App\Models\Work;
use App\Models\Person;

class OrganisationController extends BaseController 
{

	protected $controller_name 					= 'organisasi';

	public function index()
	{		
		$this->layout->page 					= view('pages.organisation.index');

		return $this->layout;
	}

	public function create($id = null)
	{
		if(is_null($id))
		{
			$this->layout->page 				= view('pages.organisation.create', compact('id'));
		}
		else
		{
			if(!in_array($id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$result								= $this->dispatch(new Getting(new Organisation, ['ID' => $id], ['created_at' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}
		
			$organisation 				 		= json_decode(json_encode($content->data), true);
			$this->layout->page 				= view('pages.organisation.create', compact('id', 'organisation'));
		}

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		$attributes 							= Input::only('name');
		$person 								= Config::get('user.id');
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new Organisation, $attributes, $id));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Organisation', $value2);
					}
				}
				else
				{
					$errors->add('Organisation', $value);
				}
			}
		}
		else
		{
			if(is_null($id))
			{
				$content_2							= $this->dispatch(new Getting(new Organisation, ['ID' => $is_success->data->id, 'withattributes' => ['branches', 'branches.charts', 'branches.charts.calendars']], ['created_at' => 'asc'] ,1, 1));

				$is_success_2 						= json_decode($content_2);

				if(!$is_success_2->meta->success || !isset($is_success_2->data->branches[0]) || !isset($is_success_2->data->branches[0]->charts[0]))
				{
					foreach ($is_success_2->meta->errors as $key => $value) 
					{
						if(is_array($value))
						{
							foreach ($value as $key2 => $value2) 
							{
								$errors->add('Organisation', $value2);
							}
						}
						else
						{
							$errors->add('Organisation', $value);
						}
					}
				}

				$work['chart_id'] 					= $is_success_2->data->branches[0]->charts[0]->id;
				$work['status'] 					= 'admin';
				$work['position'] 					= 'admin';
				$work['start'] 						= date('Y-m-d');

				$saved_work 						= $this->dispatch(new Saving(new Work, $work, null, new Person, $person));
				$is_success_3 						= json_decode($saved_work);
				
				if(!$is_success_3->meta->success)
				{
					foreach ($is_success_3->meta->errors as $key => $value) 
					{
						if(is_array($value))
						{
							foreach ($value as $key2 => $value2) 
							{
								$errors->add('Organisation', $value2);
							}
						}
						else
						{
							$errors->add('Organisation', $value);
						}
					}
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.organisations.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('local_msg', $errors)->with('alert_success', 'organisasi "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
	{
		if(!in_array($id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$result								= $this->dispatch(new Getting(new Organisation, ['ID' => $id], ['created_at' => 'asc'] ,1, 1));

		$content 							= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}
	
		$data 				 				= json_decode(json_encode($content->data), true);

		if(Input::has('start'))
		{
			$start 							= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 							= date('Y-m-d', strtotime('first day of this month'));
		}

		if(Input::has('end'))
		{
			$end 							= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 							= date('Y-m-d', strtotime('last day of next month'));
		}

		$this->layout->page 				= view('pages.organisation.show', compact('data', 'id', 'start', 'end'));

		return $this->layout;
	}

	public function edit($id)
	{
		if(!in_array($id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		return $this->create($id);
	}

	public function destroy($id)
	{
		if(Input::has('org_id'))
		{
			$id 							= Input::get('org_id');
		}
		else
		{
			$id 							= Session::get('user.organisation');
		}

		$attributes 						= ['email' => Session::get('user.email'), 'password' => Input::get('password')];

		$results 							= $this->dispatch(new Checking(new Person, $attributes));

		$content 							= json_decode($results);

		if($content->meta->success)
		{
			if(!in_array($id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Organisation, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.organisations.index')->with('local_msg', $errors)->with('alert_success', 'Organisasi "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}
