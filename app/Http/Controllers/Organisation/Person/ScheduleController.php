<?php namespace App\Http\Controllers\Organisation\Person;
use Input, Session, App, Paginator, Redirect, DB, Config, Response, DateTime, DateInterval, DatePeriod;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\ProcessLog;
use App\Models\Person;
use App\Models\Work;
use App\Models\Schedule;
use App\Models\PersonSchedule;

class ScheduleController extends BaseController
{
	protected $controller_name = 'jadwal';

	public function index($page = 1)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.person.schedule.index', compact('id', 'data', 'person'));

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$calendar 								= json_decode(json_encode($contents->data), true);
		$data 									= $calendar['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.person.schedule.create', compact('id', 'data', 'calendar'));

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('name', 'status', 'on', 'start', 'end');
		$attributes['on'] 						= date('Y-m-d', strtotime($attributes['on']));
		$attributes['start'] 					= date('H:i:s', strtotime($attributes['start']));
		$attributes['end'] 						= date('H:i:s', strtotime($attributes['end']));

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Schedule, $attributes, $id, new Person, $person_id));
		$is_success 							= json_decode($content);		
		
		if(!$is_success->meta->success)
		{
			$errors->add('Person', $is_success->meta->errors);
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.calendars.show', [$person_id, 'org_id' => $org_id])->with('alert_success', 'Jadwal kalender "' . $contents->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id)
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

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// App::abort(404);
		// }
		
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
		$this->layout->pages 			= view('pages.chart.show');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->branch 	= $branch;
		$this->layout->pages->chart 	= $chart;
		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function ajax($page = 1)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		if(Input::has('start'))
		{
			$start 								= Input::get('start');
		}
		else
		{
			$start 								= date('Y-m-d', strtotime('First Day of this month'));
		}

		if(Input::has('end'))
		{
			$end 								= Input::get('end');
		}
		else
		{
			$end 								= date('Y-m-d', strtotime('First Day of next month'));
		}

		//check if person worked or not
		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found2'], 404);
		}

		$person 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);

		//look for person calendar via work
		$search['personid'] 					= $person_id;
		$search['status'] 						= ['contract','trial','internship','permanent','previous'];
		$search['active'] 						= true;
		$search['withattributes'] 				= ['calendar'];
		$sort 									= ['start' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found3'], 404);
		}

		$work 									= json_decode(json_encode($contents->data), true);
		$wcalendar 								= $work['calendar'];

		unset($search);
		unset($sort);

		//look for schedule based on work calendar
		$search['calendarid'] 					= $wcalendar;
		$search['ondate'] 						= [$start, $end];
		$sort 									= ['on' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Schedule, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			dd($contents);
			return Response::json(['message' => 'Not Found4'], 404);
		}

		$cschedule 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);

		//look for person schedule
		$search['personid'] 					= $person_id;
		$search['ondate'] 						= [$start, $end];
		$sort 									= ['on' => 'asc'];
		$results 								= $this->dispatch(new Getting(new PersonSchedule, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		$pschedule 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);
		
		//rebuild the schedule
		$begin 									= new DateTime( $start );
		$ended 									= new DateTime( $end  );

		$interval 								= DateInterval::createFromDateString('1 day');
		$periods 								= new DatePeriod($begin, $interval, $ended);

		$workdays 								= [];
		$wd										= ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday', 'monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];
		
		$workday 								= explode(',', $wcalendar['workdays']);

		foreach ($workday as $key => $value) 
		{
			if($value!='')
			{
				$value 							= str_replace(' ', '', $value);
				$workdays[]						= $wd[strtolower($value)];
			}
		}

		$schedule 								= [];
		$date 									= [];
		$k 										= 0;

		foreach ( $periods as $period )
		{
			foreach($pschedule as $i => $sh)	
			{
				if($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])))
				{
					$schedule[$k]['id']				= $k;
					$schedule[$k]['title'] 			= $sh['name'];
					$schedule[$k]['start']			= $sh['on'].'T'.$sh['start'];
					$schedule[$k]['end']			= $sh['on'].'T'.$sh['end'];
					$schedule[$k]['status']			= $sh['status'];
					$schedule[$k]['data_target']	= '#modal_schedule';
					$schedule[$k]['ed_action']		= route('hr.person.schedules.store', ['id' => $sh['id'], 'org_id' => $org_id, 'person_id' => $person_id]);
					$schedule[$k]['del_action']		= route('hr.person.schedules.delete', ['id' => $sh['id'], 'org_id' => $org_id, 'person_id' => $person_id]);

					switch (strtolower($sh['status'])) 
					{
						case 'presence_indoor':
							$schedule[$k]['backgroundColor']= '#6D7DDA';
							$schedule[$k]['color']			= '#6D7DDA';
							break;
						case 'presence_outdoor':
							$schedule[$k]['backgroundColor']= '#dgedf7';
							$schedule[$k]['color']			= '#31708f';
							break;
						case 'absence_workleave':
							$schedule[$k]['backgroundColor']= '#EDA7A7';
							$schedule[$k]['color']			= '#EDA7A7';
						break;
						case 'absence_not_workleave':
							$schedule[$k]['backgroundColor']= '#9E998A';
							$schedule[$k]['color']			= '#9E998A';
							break;
						default:
							$schedule[$k]['backgroundColor']= '#fef8e3';
							$schedule[$k]['color']			= '#8abd3b';
							break;
					}

					$date[]							= $period->format('Y-m-d');
					$k++;
				}
			}

			if(!in_array($period->format('Y-m-d'), $date))
			{
				foreach($cschedule as $i => $sh)	
				{
					if($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])) && !in_array($period->format('Y-m-d'), $date))
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['title'] 			= $sh['name'];
						$schedule[$k]['start']			= $sh['on'].'T'.$sh['start'];
						$schedule[$k]['end']			= $sh['on'].'T'.$sh['end'];
						$schedule[$k]['status']			= $sh['status'];
										
						switch (strtolower($sh['status'])) 
						{
							case 'presence_indoor':
								$schedule[$k]['backgroundColor']= '#31708f';
								$schedule[$k]['color']			= '#31708f';
								break;
							case 'presence_outdoor':
								$schedule[$k]['backgroundColor']= '#ag4442';
								$schedule[$k]['color']			= '#ag4442';
								break;
							case 'absence_workleave':
								$schedule[$k]['backgroundColor']= '#23AB2F';
								$schedule[$k]['color']			= '#23AB2F';
							break;
							case 'absence_not_workleave':
								$schedule[$k]['backgroundColor']= '#3C763D';
								$schedule[$k]['color']			= '#3C763D';
								break;
							default:
								$schedule[$k]['backgroundColor']= '#8abd3b';
								$schedule[$k]['color']			= '#8abd3b';
								break;
						}

						$date[]							= $period->format('Y-m-d');
						$k++;
					}
				}

				if(!in_array($period->format('Y-m-d'), $date))
				{
					if(in_array(strtolower($period->format('l')), $workdays))
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['title'] 			= 'Masuk Kerja';
						$schedule[$k]['start']			= $period->format('Y-m-d').'T'.$sh['start'];
						$schedule[$k]['end']			= $period->format('Y-m-d').'T'.$sh['end'];
						$schedule[$k]['status']			= 'presence_indoor';
						$schedule[$k]['backgroundColor']= '#31708F';
						$schedule[$k]['color']			= '#31708F';

						$date[]							= $period->format('Y-m-d');
						$k++;
					}
					else
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['title'] 			= 'Libur';
						$schedule[$k]['start']			= $period->format('Y-m-d').'T'.'00:00:00';
						$schedule[$k]['end']			= $period->format('Y-m-d').'T'.'00:00:00';
						$schedule[$k]['status']			= 'absence_not_workleave';
						$schedule[$k]['backgroundColor']= '#D78409';
						$schedule[$k]['color']			= '#D78409';

						$date[]							= $period->format('Y-m-d');
						$k++;
					}
				}
			}
		}

		// log	
		$search 								= ['personid' => $person_id, 'ondate'=> [$start, $end]];
		$sort 									= ['on' => 'asc'];

		$results 								= $this->dispatch(new Getting(new ProcessLog, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}
		
		$contents 								= json_decode($results);
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$logs 									= json_decode(json_encode($contents->data), true);

		foreach($logs as $i => $log)	
		{
			$schedule[$k]['id']				= $log['id'];
			$schedule[$k]['title'] 			= $log['name'];

			if ((strtotime($log['fp_start']) < strtotime($log['fp_end'])) | (strtotime($log['fp_start']) != strtotime($log['fp_end'])))
			{
				$schedule[$k]['start']		= $log['on'].'T'.$log['fp_start'];
				$schedule[$k]['end']		= $log['on'].'T'.$log['fp_end'];
			}
			else 
			{
				$schedule[$k]['start']		= $log['on'].'T'.$log['fp_start'];
			}

			$schedule[$k]['status']			= $log['tooltip'];
			$schedule[$k]['backgroundColor']= '#9c27b0';
			$schedule[$k]['mode']			= 'log';				
			$k++;
		}
		return Response::json($schedule);
	}
}