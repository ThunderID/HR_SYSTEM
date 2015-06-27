<?php namespace App\Http\Controllers\Organisation\Branch\Chart;
use Input, Session, App, Paginator, Redirect, DB, Config;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Chart;
use App\Models\Calendar;
use App\Models\Follow;
use App\Models\Person;

class CalendarController extends BaseController
{
	protected $controller_name = 'kalender';

	public function index($page = 1)
	{
		// ---------------------- LOAD DATA ----------------------
		if(Input::has('org_id'))
		{
			$org_id 					= Input::get('org_id');
		}
		else
		{
			$org_id 					= Session::get('user.organisation');
		}

		if(Input::has('branch_id'))
		{
			$branch_id 					= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('chart_id'))
		{
			$id 						= Input::get('chart_id');
		}
		else
		{
			App::abort(404);
		}
	
		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search 						= ['id' => $id, 'branchid' => $branch_id, 'organisationid' => $org_id, 'withattributes' => ['branch', 'branch.organisation']];
		$results 						= $this->dispatch(new Getting(new Chart, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$chart 							= json_decode(json_encode($contents->data), true);
		$branch 						= $chart['branch'];
		$data 							= $chart['branch']['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 			= view('pages.organisation.branch.chart.follow.index');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->branch 	= $branch;
		$this->layout->pages->chart 	= $chart;
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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
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

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $branch_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['branch', 'branch.organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$chart 									= json_decode(json_encode($contents->data), true);
		$data 									= $chart['branch']['organisation'];
		$branch 								= $chart['branch'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.branch.chart.follow.create', compact('id', 'data', 'branch', 'chart'));

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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
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

		if(Input::has('calendar_id'))
		{
			$cal_id 							= Input::get('calendar_id');
		}
		else
		{
			App::abort(404);
		}
		
		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search['id'] 							= $chart_id;
		$search['branchid'] 					= $branch_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}


		$errors 								= new MessageBag();

		DB::beginTransaction();

		$attributes 							= ['chart_id' => $chart_id];

		$content 								= $this->dispatch(new Saving(new Follow, $attributes, $id, new Calendar, $cal_id));
		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Chart', $value2);
					}
				}
				else
				{
					$errors->add('Chart', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.chart.calendars.index', ['chart_id' => $chart_id, 'branch_id' => $branch_id, 'org_id' => $org_id])->with('alert_success', 'Kalender Kerja Jabatan "' . $contents->data->name. '" sudah disimpan');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function destroy($id)
	{
		$attributes 						= ['email' => Session::get('user.email'), 'password' => Input::get('password')];

		$results 							= $this->dispatch(new Checking(new Person, $attributes));

		$content 							= json_decode($results);

		if($content->meta->success)
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

			if(Input::has('branch_id'))
			{
				$branch_id 							= Input::get('branch_id');
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

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['id' => $chart_id, 'organisationid' => $org_id, 'branchid' => $branch_id];
			$results 						= $this->dispatch(new Getting(new Chart, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'chartid' => $chart_id];
			$results 						= $this->dispatch(new Getting(new Follow, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Follow, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.chart.calendars.index', ['chart_id' => $chart_id, 'branch_id' => $branch_id, 'org_id' => $org_id])->with('alert_success', 'Kalender Kerja sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}