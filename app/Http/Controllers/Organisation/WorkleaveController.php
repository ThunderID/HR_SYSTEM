<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Workleave;
use App\Models\PersonWorkleave;

class WorkleaveController extends BaseController 
{

	protected $controller_name 						= 'cuti';

	public function index()
	{		

		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$search['id']								= $org_id;
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);

		$this->layout->page 						= view('pages.workleave.index', compact('data'));

		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$search['id']							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		$this->layout->page 					= view('pages.workleave.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}
		
		$attributes 							= Input::only('name', 'quota');

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Workleave, $attributes, $id, new Organisation, $org_id));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			$errors->add('Workleave', $is_success->meta->errors);
		}

		if(isset($attributes['persons']))
		{
			foreach ($attributes['persons'] as $key => $value) 
			{
				$workleave						= $attributes['person']['workleave'];
				if(isset($attributes['person']['workleave']['id']) && $attributes['person']['workleave']['id']!='' && !is_null($attributes['person']['workleave']['id']))
				{
					$workleave['id']			= $attributes['person']['workleave']['id'];
				}
				else
				{
					$workleave['id']			= null;
				}
				$workleave['workleave_id']		= $id;

				$saved_workleave 				= $this->dispatch(new Saving(new PersonWorkleave, $workleave, $workleave['id'], new Person, $value['id']));
				$is_success_2 					= json_decode($saved_workleave);

				if(!$is_success_2->meta->success)
				{
					$errors->add('Workleave', $is_success_2->meta->errors);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.workleaves.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('alert_success', 'Cuti "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show()
	{
		$this->layout->page 	= view('pages.workleave.show');

		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

}
