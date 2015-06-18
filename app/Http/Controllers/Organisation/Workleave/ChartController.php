<?php namespace App\Http\Controllers\Organisation\Workleave;
use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Workleave;
use App\Models\PersonWorkleave;

class ChartController extends BaseController
{
	protected $controller_name = 'jabatan';
	
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

		if(Input::has('cal_id'))
		{
			$cal_id 							= Input::get('cal_id');
		}
		else
		{
			App::abort(404);
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// App::abort(404);
		// }

		$search['id'] 							= $cal_id;
		$search['organisationid'] 				= $org_id;
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
		$this->layout->pages 					= view('pages.workleave.chart.create', compact('id', 'data', 'workleave'));

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
			$org_id 							= Session::get('user.organisation');
		}

		if(Input::has('cal_id'))
		{
			$cal_id 							= Input::get('cal_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $cal_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('name', 'status', 'on', 'start', 'end');
		$attributes['on'] 						= date('Y-m-d', strtotime($attributes['on']));

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new PersonWorkleave, $attributes, $id, new Workleave, $cal_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			$errors->add('Workleave', $is_success->meta->errors);
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.calendars.show', [$cal_id, 'org_id' => $org_id])->with('alert_success', 'Jadwal kalender "' . $contents->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}
}