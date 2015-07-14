<?php namespace App\Http\Controllers\Organisation\Workleave;
use Input, Session, App, Paginator, Redirect, DB, Config;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Chart;
use App\Models\Person;
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
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('workleave_id'))
		{
			$workleave_id 							= Input::get('workleave_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('chart_id'))
		{
			$chart_id 							= Input::get('chart_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $workleave_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$search['id'] 							= $chart_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['works'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('start', 'end', 'is_default');
		$attributes['start'] 					= date('Y-m-d', strtotime($attributes['start']));
		$attributes['end'] 						= date('Y-m-d', strtotime($attributes['end']));
		$attributes['workleave_id']				= $workleave_id;

		$errors 								= new MessageBag();

		DB::beginTransaction();

		foreach ($contents->data->works as $key => $value) 
		{
			$content 							= $this->dispatch(new Saving(new PersonWorkleave, $attributes, $id, new Person, $value->id));
			$is_success 						= json_decode($content);
			
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
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.workleaves.index', [$workleave_id, 'workleave_id' => $workleave_id, 'org_id' => $org_id])->with('alert_success', 'Jadwal kalender "' . $contents->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}
}