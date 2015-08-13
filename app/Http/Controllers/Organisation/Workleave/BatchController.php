<?php namespace App\Http\Controllers\Organisation\Workleave;
use Input, Session, App, Paginator, Redirect, DB, Config, DateInterval, DatePeriod, DateTime, Queue, Response;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Chart;
use App\Models\Person;
use App\Models\Workleave;
use App\Models\Queue;
use App\Models\PersonWorkleave;
use App\Commands\BatchHRProcess;

class BatchController extends BaseController
{
	protected $controller_name = 'batch';
	
	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('workleave_id'))
		{
			$workleave_id 						= Input::get('workleave_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $workleave_id;
		$search['organisationid'] 				= $org_id;
		$search['status'] 						= 'CN';
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$workleave 								= json_decode(json_encode($contents->data), true);
		$data 									= $workleave['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.workleave.batch.create', compact('id', 'data', 'workleave'));

		return $this->layout;
	}
	
	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('workleave_id'))
		{
			$workleave_id 						= Input::get('workleave_id');
		}
		else
		{
			App::abort(404);
		}

		$errors 								= new MessageBag();

		$input 									= Input::only('start', 'joint_start', 'joint_end');
		$input['workleave_id'] 					= $workleave_id;
		$input['org_id'] 						= $org_id;

		$start 									= date('Y',strtotime($input['start']));
		$begin 									= new DateTime( Input::get('joint_start') );
		$end 									= new DateTime( Input::get('joint_end').' + 1 day' );

		if($start < date('Y') || $begin->format('Y') < date('Y') || $end->format('Y') < date('Y'))
		{
			$errors->add('Workleave', 'Batch pemberian cuti hanya dapat dilakukan untuk tahun berikut.');

		}

		$search['id'] 							= $workleave_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$workleave 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);

		$search['workleaveid'] 					= $workleave_id;
		$sort 									= ['created_at' => 'asc'];
		$results 								= $this->dispatch(new Getting(new FollowWorkleave, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}
		
		$works 									= json_decode(json_encode($contents->data), true);

		$attributes['workleave_id'] 			= $workleave_id;
		$attributes['created_by'] 				= Session::get('loggedUser');
		$attributes['start'] 					= date('Y-m-d', strtotime($entry_date. ' + 1 year'));
		$attributes['end'] 						= date('Y-m-d', strtotime(' last day of december '.date('Y', strtotime($attributes['start'])).' + 3 months'));
		$attributes['name'] 					= $workleave['name'].' '.date('Y', strtotime($attributes['start']));
		$attributes['quota'] 					= $workleave['quota'];
		$attributes['status'] 					= 'CN';
		$attributes['notes'] 					= 'Batch '.$attributes['name'];

		$queattr['created_by'] 					= Session::get('loggedUser');
		$queattr['process_name'] 				= 'hr:queue personworkleavebatchcommand';
		$queattr['parameter'] 					= json_encode($attributes);
		$queattr['total_process'] 				= count($works);
		$queattr['task_per_process']			= 10;
		$queattr['process_number'] 				= 0;
		$queattr['total_task'] 					= count($works)/10;
		$queattr['message'] 					= 'Initial Queue';

		$content 								= $this->dispatch(new Saving(new Queue, $queattr, null));
		$is_success_2 							= json_decode($content);

		if(!$is_success_2->meta->success)
		{
			foreach ($is_success_2->meta->errors as $key2 => $value2) 
			{
				if(is_array($value2))
				{
					foreach ($value2 as $key3 => $value3) 
					{
						$errors->add('Batch', $value3);
					}
				}
				else
				{
					$errors->add('Batch', $value2);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.workleaves.index', [$workleave_id, 'workleave_id' => $workleave_id, 'org_id' => $org_id])->with('alert_success', 'Cuti "' . $workleave['name']. '" sedang menyimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}
}