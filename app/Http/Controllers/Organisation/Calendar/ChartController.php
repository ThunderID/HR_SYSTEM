<?php namespace App\Http\Controllers\Organisation\Calendar;
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

class ChartController extends BaseController
{
	protected $controller_name = 'follow';

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

		if(Input::has('cal_id'))
		{
			$id 						= Input::get('cal_id');
		}
		else
		{
			App::abort(404);
		}
	
		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search 						= ['id' => $id, 'organisationid' => $org_id, 'withattributes' => ['organisation']];
		$results 						= $this->dispatch(new Getting(new Calendar, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$calendar 						= json_decode(json_encode($contents->data), true);
		$data 							= $calendar['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 			= view('pages.organisation.calendar.chart.index');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->calendar 	= $calendar;
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

		if(Input::has('cal_id'))
		{
			$cal_id 							= Input::get('cal_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $cal_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$calendar 								= json_decode(json_encode($contents->data), true);
		$data 									= $calendar['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.calendar.chart.create', compact('id', 'data', 'calendar'));

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

		if(Input::has('chart_id'))
		{
			$chart_id 							= Input::get('chart_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('cal_id'))
		{
			$cal_id 							= Input::get('cal_id');
		}
		else
		{
			App::abort(404);
		}
		
		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search['id'] 							= $cal_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 1));
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
						$errors->add('Calendar', $value2);
					}
				}
				else
				{
					$errors->add('Calendar', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.calendar.charts.index', ['cal_id' => $cal_id, 'org_id' => $org_id])->with('alert_success', 'Jabatan "' . $contents->data->name. '" sudah disimpan');
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
		$attributes 						= ['username' => Session::get('user.username'), 'password' => Input::get('password')];

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

			if(Input::has('cal_id'))
			{
				$cal_id 							= Input::get('cal_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['id' => $cal_id, 'organisationid' => $org_id];
			$results 						= $this->dispatch(new Getting(new Calendar, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'calendarid' => $cal_id];
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
				return Redirect::route('hr.calendar.charts.index', ['cal_id' => $cal_id, 'org_id' => $org_id])->with('alert_success', 'Kalender Kerja sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}