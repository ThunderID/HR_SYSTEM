<?php namespace App\Http\Controllers\Organisation\Calendar;
use Input, Session, App, Paginator, Redirect, DB, Config, Response, DateTime, DateInterval, DatePeriod;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Console\Commands\Getting;
use App\Models\Calendar;
use App\Models\Schedule;
use App\Models\Person;
use App\Models\Queue;

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
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('cal_id'))
		{
			$cal_id 							= Input::get('cal_id');
		}
		else
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
			return Response::json(['message' => 'Not Found'], 404);
		}
		$calendar 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);

		$search['calendarid'] 					= $cal_id;
		$search['ondate'] 						= [$start, $end];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Schedule, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		$schedules 								= json_decode(json_encode($contents->data), true);

		$begin 									= new DateTime( $start );
		$ended 									= new DateTime( $end  );

		$interval 								= DateInterval::createFromDateString('1 day');
		$periods 								= new DatePeriod($begin, $interval, $ended);
		
		$workdays 								= [];
		$wd										= ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday', 'monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];
		
		$workday 								= explode(',', $calendar['workdays']);

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
		$j 										= 0;
		foreach ( $periods as $period )
		{
			$j++;
			foreach($schedules as $i => $sh)	
			{
				if($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])))
				{
				
						$schedule[$k]['mode']			= 'edit';
						$schedule[$k]['top_title']		= 'Jadwal';
						$schedule[$k]['data_target']	= '#modal_schedule';
						$schedule[$k]['id']				= $sh['id'];
						$schedule[$k]['title'] 			= $sh['name'];
						$schedule[$k]['start']			= $sh['on'].'T'.$sh['start'];
						$schedule[$k]['end']			= $sh['on'].'T'.$sh['end'];
						$schedule[$k]['status']			= $sh['status'];					
						$schedule[$k]['ed_action']		= route('hr.calendar.schedules.store', ['id' => $sh['id'], 'org_id' => $org_id, 'cal_id' => $cal_id]);
						$schedule[$k]['del_action']		= route('hr.calendar.schedules.delete', ['id' => $sh['id'], 'org_id' => $org_id, 'cal_id' => $cal_id]);

						switch (strtolower($sh['status'])) 
						{
							case 'hb':
								$schedule[$k]['label']= 'dark-blue';
								// $schedule[$k]['title_info']= 
								break;
							case 'dn':
								$schedule[$k]['label']= 'blue';
								break;
							case 'ul' : case 'cn' : case  'ci' : case 'cb':
								$schedule[$k]['label']= 'green-young';
							break;
							case 'ss' : case 'sl' :
								$schedule[$k]['label']= 'green';
								break;
							case 'l' :
								$schedule[$k]['label']= 'label label-magenta';
								break;
							default:
								$schedule[$k]['label']= 'green';
								break;
						}					
						// $k++;
						// $schedule[$k]['mode']			= 'create';
						// $schedule[$k]['data_target']	= '#modal_schedule';
						// $schedule[$k]['title'] 			= 'Tambah Jadwal';
						// $schedule[$k]['start']			= $sh['on'];
						// $schedule[$k]['end']			= $sh['on'];
						// $schedule[$k]['status']			= $sh['status'];
						// $schedule[$k]['add_action']		= route('hr.calendar.schedules.store', ['org_id' => $org_id, 'cal_id' => $cal_id]);	
						// $schedule[$k]['label']			= 'primary';
					

					$date[]							= $period->format('Y-m-d');
					$k++;
				}
			}

			if(!in_array($period->format('Y-m-d'), $date))
			{
				$schedule[$k]['mode']				= 'create';
				$schedule[$k]['data_target']		= '#modal_schedule';
				$schedule[$k]['add_action']			= route('hr.calendar.schedules.store', ['org_id' => $org_id, 'cal_id' => $cal_id]);				
				if(in_array(strtolower($period->format('l')), $workdays))
				{
					$schedule[$k]['id']				= $calendar['id'];
					$schedule[$k]['title'] 			= 'Masuk Kerja';
					$schedule[$k]['start']			= $period->format('Y-m-d').'T'.$calendar['start'];
					$schedule[$k]['end']			= $period->format('Y-m-d').'T'.$calendar['end'];
					$schedule[$k]['status']			= 'HB';
					$schedule[$k]['label']			= 'label-schedule label-gray';
					$schedule[$k]['info_default']	= 'yes';

					$date[]							= $period->format('Y-m-d');
					$k++;
				}
				else
				{
					$schedule[$k]['id']				= $period->format('Ymd');
					$schedule[$k]['title'] 			= 'Tidak Masuk Kerja';
					$schedule[$k]['start']			= $period->format('Y-m-d').'T'.'00:00:00';
					$schedule[$k]['end']			= $period->format('Y-m-d').'T'.'00:00:00';
					$schedule[$k]['status']			= 'L';
					$schedule[$k]['label']			= 'label-schedule label-magenta';
					$schedule[$k]['info_default']	= 'yes';

					$date[]							= $period->format('Y-m-d');
					$k++;
				}
			}
		}

		return Response::json($schedule);		
	}
	
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
		$this->layout->pages 					= view('pages.schedule.create', compact('id', 'data', 'calendar'));

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
		$search['activeworks'] 					= true;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$calendar 								= json_decode(json_encode($contents->data), true);

		$attributes 							= Input::only('name', 'status', 'start', 'end');
		$attributes['created_by'] 				= Session::get('loggedUser');

		$attributes['start'] 					= date('H:i:s', strtotime($attributes['start']));
		$attributes['end'] 						= date('H:i:s', strtotime($attributes['end']));

		if(in_array(strtoupper($attributes['status']), ['CN', 'CB', 'CI', 'L']))
		{
			$attributes['start'] 				= '00:00:00';
			$attributes['end'] 					= '00:00:00';
		}

		$errors 								= new MessageBag();

		DB::beginTransaction();

		if(Input::has('on'))
		{
			$begin 								= new DateTime( Input::get('on') );
			$ended 								= new DateTime( Input::get('on').' + 1 day' );
			$maxend 							= new DateTime( Input::get('on').' + 7 days' );
		}
		elseif(Input::has('onstart') && Input::has('onend'))
		{
			$begin 								= new DateTime( Input::get('onstart') );
			$ended 								= new DateTime( Input::get('onend').' + 1 day' );
			$maxend 							= new DateTime( Input::get('onstart').' + 7 days' );
		}
		else
		{
			$errors->add('Calendar', 'Tanggal Tidak Valid');
		}

		if(isset($ended)  && $ended->format('Y-m-d') <= $begin->format('Y-m-d'))
		{
			$errors->add('Calendar', 'Tanggal akhir harus lebih besar dari tanggal mulai. Gunakan single date untuk tanggal manual');
		}

		if(isset($ended)  && $ended->format('Y-m-d') > $maxend->format('Y-m-d'))
		{
			$errors->add('Calendar', 'Maksimal range adalah satu minggu (7 Hari) ');
		}

		if(Input::has('affect'))
		{
			if($calendar['import_from_id']!=0)
			{
				unset($search);
				unset($sort);

				$search['organisationid'] 			= $org_id;
				$search['activeworks'] 				= true;
				$search['parentid'] 				= $cal_id;
				$sort 								= ['name' => 'asc'];
				$results 							= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 100));
				$contents 							= json_decode($results);

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$calendars 							= json_decode(json_encode($contents->data), true);
			}

			$calendars[]							= $calendar;
		}

		if(!$errors->count())
		{
			$interval 								= DateInterval::createFromDateString('1 day');
			$periods 								= new DatePeriod($begin, $interval, $ended);

			foreach ( $periods as $period )
			{
				$attributes['on'] 					= $period->format('Y-m-d');
				
				if(Input::has('affect'))
				{
					$queattr['created_by'] 			= Session::get('loggedUser');
					if(in_array(strtoupper($attributes['status']), ['CN', 'CB', 'CI']))
					{
						$queattr['process_name'] 	= 'hr:personworkleavebatch';
						$queattr['process_option'] 	= 'personstaken';
					}
					else
					{
						$queattr['process_name'] 	= 'hr:schedulebatch';
					}
					
					$queattr['parameter'] 			= json_encode($attributes);
					$queattr['task_per_process']	= 10;
					$queattr['process_number'] 		= 0;
					$queattr['message'] 			= 'Initial Queue';

					foreach ($calendars as $key => $value) 
					{
						$attributes['associate_calendar_id'] 	= $value['id'];

					
						$queattr['total_process'] 	= count($value['works']);
						$queattr['total_task'] 		= count($value['works'])/10;

						$content 					= $this->dispatch(new Saving(new Queue, $queattr, null));
						$is_success_2 				= json_decode($content);

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
					}
				}
				else
				{
					$attributes['associate_calendar_id'] 	= $calendar['id'];

					$queattr['created_by'] 		= Session::get('loggedUser');
					$queattr['process_name'] 	= 'hr:schedulebatch';
					$queattr['parameter'] 		= json_encode($attributes);
					$queattr['total_process'] 	= count($calendar['works']);
					$queattr['task_per_process']= 10;
					$queattr['process_number'] 	= 0;
					$queattr['total_task'] 		= count($calendar['works'])/10;
					$queattr['message'] 		= 'Initial Queue';

					$content 					= $this->dispatch(new Saving(new Queue, $queattr, null));
					$is_success_2 				= json_decode($content);

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
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.calendars.show', [$cal_id, 'org_id' => $org_id])->with('alert_info', 'Jadwal kalender sedang disimpan');
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
			if(Input::has('org_id'))
			{
				$org_id 					= Input::get('org_id');
			}
			else
			{
				$org_id 					= Session::get('user.organisationid');
			}

			if(Input::has('cal_id'))
			{
				$cal_id 					= Input::get('cal_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['organisationid' => $org_id, 'id' => $cal_id];
			$results 						= $this->dispatch(new Getting(new Calendar, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'calendarid' => $cal_id];
			$results 						= $this->dispatch(new Getting(new Schedule, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Schedule, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.calendars.show', ['id' => $cal_id, 'org_id' => $org_id, 'cal_id' => $cal_id])->with('alert_success', 'Jadwal "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}