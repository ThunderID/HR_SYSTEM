<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Calendar;
use App\Models\Schedule;
use App\Models\PersonCalendar;
use App\Models\Follow;

class CalendarController extends BaseController 
{

	protected $controller_name 					= 'kalender';

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

		$this->layout->page 						= view('pages.calendar.index', compact('data'));

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

		$this->layout->page 					= view('pages.calendar.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}
		
		$attributes 							= Input::only('name', 'workdays', 'start', 'end');
		$attributes['start']					= date('H:i:s', strtotime($attributes['start']));
		$attributes['end']						= date('H:i:s', strtotime($attributes['end']));
		
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
		
		$content 								= $this->dispatch(new Saving(new Calendar, $attributes, $id, new Organisation, $org_id));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			$errors->add('Calendar', $is_success->meta->errors);
		}

		if(isset($attributes['schedules']))
		{
			foreach ($attributes['schedules'] as $key => $value) 
			{
				if(is_array($value['on']))
				{
					$begin 						= new DateTime( $value['on'][0] );
					$end 						= new DateTime( ($value['on'][1].' + 1 day') );

					$interval 					= DateInterval::createFromDateString('1 day');
					$periods 					= new DatePeriod($begin, $interval, $end);

					foreach ( $periods as $period )
					{
						$schedule				= $value;
						$schedule['on']			= $period->format('Y-m-d');
						if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
						{
							$schedule['id']		= $value['id'];
						}
						else
						{
							$schedule['id']		= null;
						}

						$saved_schedule 		= $this->dispatch(new Saving(new Schedule, $schedule, $schedule['id'], new Calendar, $is_success->data->id));
						$is_success_2 			= json_decode($saved_schedule);

						if(!$is_success_2->meta->success)
						{
							$errors->add('Calendar', $is_success->meta->errors);
						}
					}
				}
				else
				{
					$schedule					= $value;
					if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
					{
						$schedule['id']			= $value['id'];
					}
					else
					{
						$schedule['id']			= null;
					}

					$saved_schedule 			= $this->dispatch(new Saving(new Schedule, $schedule, $schedule['id'], new Calendar, $is_success->data->id));
					$is_success_2 				= json_decode($saved_schedule);

					if(!$is_success_2->meta->success)
					{
						$errors->add('Calendar', $is_success->meta->errors);
					}
				}
			}
		}

		if(isset($attributes['persons']))
		{
			foreach ($attributes['persons'] as $key => $value) 
			{
				$personcalendar					= $value;
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$personcalendar['id']		= $value['id'];
				}
				else
				{
					$personcalendar['id']		= null;
				}

				$saved_person 					= $this->dispatch(new Saving(new PersonCalendar, $personcalendar, $personcalendar['id'], new Calendar, $is_success->data->id));
				$is_success_2 					= json_decode($saved_person);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Calendar', $is_success->meta->errors);
				}
			}
		}

		if(isset($attributes['charts']))
		{
			foreach ($attributes['charts'] as $key => $value) 
			{
				$follow							= $value;
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$follow['id']				= $value['id'];
				}
				else
				{
					$follow['id']				= null;
				}

				$saved_chart 					= $this->dispatch(new Saving(new Follow, $follow, $follow['id'], new Calendar, $is_success->data->id));
				$is_success_2 					= json_decode($saved_chart);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Calendar', $is_success->meta->errors);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.calendars.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('alert_success', 'kalender "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show()
	{
		$this->layout->page 	= view('pages.calendar.show');

		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

}
