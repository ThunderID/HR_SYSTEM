<?php namespace App\Http\Controllers\Organisation\Workleave;
use Input, Session, App, Paginator, Redirect, DB, Config, Response;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;

use App\Models\Work;
use App\Models\Workleave;
use App\Models\FollowWorkleave;
use App\Models\Person;

class WorkController extends BaseController
{
	protected $controller_name = 'followworkleave';

	public function store($id = null)
	{
		if(Input::has('follow_workleave_id') && Input::Get('follow_workleave_id')!=0)
		{
			$id 								= Input::get('follow_workleave_id');
		}
		else
		{
			$id 								= null;
		}

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			return Response::json([], 404);
		}

		if(Input::has('work_id') && Input::Get('work_id')!=0)
		{
			$work_id 							= Input::get('work_id');
		}

		if(Input::has('workleave_id') && Input::Get('workleave_id')!=0)
		{
			$workleaveid 						= Input::get('workleave_id');
		}
		else
		{
			return Response::json([], 404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			return Response::json([], 404);
		}
		
		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$search['checkwork'] 					= true;
		$search['currentwork'] 					= null;
		$sort 									= ['name' => 'asc'];

		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json([], 404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];
		if((!isset($work_id) && isset($person['works'][0])) || $work_id==0)
		{
			$work_id 							= $person['works'][0]['pivot']['id'];
		}
		elseif(isset($work_id))
		{
			unset($search);
			unset($sort);
			$search['id'] 							= $work_id;
			$search['personid'] 					= $person_id;
			$search['active'] 						= true;
			$sort 									= [];

			$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
			$contents 								= json_decode($results);

			if(!$contents->meta->success)
			{
				return Response::json([], 404);
			}
		}
		else
		{
			return Response::json([], 404);
		}


		$errors 								= new MessageBag();

		DB::beginTransaction();

		$attributes 							= ['work_id' => $work_id];

		$content 								= $this->dispatch(new Saving(new FollowWorkleave, $attributes, $id, new Workleave, $workleaveid));
		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Workleave', $value2);
					}
				}
				else
				{
					$errors->add('Workleave', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.person.workleaves.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_success', 'Kuota Cuti Karyawan Sudah Berubah ');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}
}