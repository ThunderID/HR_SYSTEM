<?php namespace App\Http\Controllers\Organisation\Person;
use Input, Session, App, Paginator, Redirect, DB, Config, Response, DateTime, DateInterval, DatePeriod;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\ProcessLog;
use App\Models\Person;
use App\Models\Work;
use App\Models\Schedule;
use App\Models\PersonSchedule;
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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 				= Session::get('user.chartpath');
		}
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.person.schedule.index', compact('id', 'data', 'person'));

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
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
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
		$this->layout->pages 					= view('pages.organisation.person.schedule.create', compact('id', 'data', 'calendar'));

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

		$attributes 							= Input::only('name', 'status', 'start', 'end');
		$attributes['created_by'] 				= Session::get('loggedUser');
		if(in_array(strtoupper($attributes['status']), ['CN', 'SS', 'SL', 'CB', 'CI', 'UL', 'L']))
		{
			$attributes['start']				= '00:00:00';
			$attributes['end']					= '00:00:00';
		}
		else
		{
			$attributes['start'] 				= date('H:i:s', strtotime($attributes['start']));
			$attributes['end'] 					= date('H:i:s', strtotime($attributes['end']));
		}

		$errors 								= new MessageBag();

		DB::beginTransaction();

		if(Input::has('on'))
		{
			$begin 								= new DateTime( Input::get('on') );
			$ended 								= new DateTime( Input::get('on').' + 1 day' );
			$maxend 							= new DateTime( Input::get('on').' + 3 days' );
		}
		elseif(Input::has('onstart') && Input::has('onend'))
		{
			$begin 								= new DateTime( Input::get('onstart') );
			$ended 								= new DateTime( Input::get('onend').' + 1 day' );
			$maxend 							= new DateTime( Input::get('onstart').' + 3 days' );
		}
		else
		{
			$errors->add('Person', 'Tanggal Tidak Valid');
		}

		if(isset($ended) && $ended->format('Y-m-d') <= $begin->format('Y-m-d'))
		{
			$errors->add('Person', 'Tanggal akhir harus lebih besar dari tanggal mulai. Gunakan single date untuk tanggal manual');
		}

		if((int)Session::Get('user.menuid')==2)
		{
			$dateline 						= date('Y-m-d', strtotime($begin->format('Y-m-d'). ' + 2 months'));

			if($dateline < date('Y-m-d'))
			{
				$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 2 Bulan');
				
				return Redirect::back()->withErrors($errors)->withInput();
			}
		}

		if((int)Session::Get('user.menuid')==3)
		{
			$dateline 						= date('Y-m-d', strtotime($begin->format('Y-m-d'). ' + 7 days'));

			if($dateline < date('Y-m-d'))
			{
				$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 7 hari');
				
				return Redirect::back()->withErrors($errors)->withInput();
			}
		}

		if(!$errors->count() && isset($ended) && $ended->format('Y-m-d') <= $maxend->format('Y-m-d'))
		{
			$interval 								= DateInterval::createFromDateString('1 day');
			$periods 								= new DatePeriod($begin, $interval, $ended);

			foreach ( $periods as $period )
			{
				$attributes['on'] 					= $period->format('Y-m-d');
				$content 							= $this->dispatch(new Saving(new PersonSchedule, $attributes, $id, new Person, $person_id));
				$is_success 						= json_decode($content);

				if(!$is_success->meta->success)
				{
					foreach ($is_success->meta->errors as $key => $value) 
					{
						if(is_array($value))
						{
							foreach ($value as $key2 => $value2) 
							{
								$errors->add('Person', $value2);
							}
						}
						else
						{
							$errors->add('Person', $value);
						}
					}
				}
			}
		}

		elseif(!$errors->count())
		{
			$interval 					= DateInterval::createFromDateString('1 day');
			$periods 					= new DatePeriod($begin, $interval, $ended);

			$attributes['onstart'] 		= $begin;
			$attributes['onend'] 		= $ended;
			$attributes['associate_person_id'] 	= $person_id;

			$queattr['created_by'] 		= Session::get('loggedUser');
			$queattr['process_name'] 	= 'hr:personschedulebatch';
			$queattr['parameter'] 		= json_encode($attributes);
			$queattr['total_process'] 	= ceil(iterator_count($periods)/10);
			$queattr['task_per_process']= 10;
			$queattr['process_number'] 	= 0;
			$queattr['total_task'] 		= iterator_count($periods);
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

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.person.schedules.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_info', 'Jadwal Pribadi sedang disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
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
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			return Response::json(['message' => 'Not Found'], 404);
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
			return Response::json(['message' => 'Not Found'], 404);
		}

		$person 								= json_decode(json_encode($contents->data), true);

		unset($search);
		unset($sort);

		//look for person calendar via work
		$search['active'] 						= $end;
		$search['personid'] 					= $person_id;
		$search['status'] 						= ['contract','probation','internship','permanent','others'];
		$search['withattributes'] 				= ['calendar'];
		$sort 									= ['start' => 'desc'];
		$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		$work 									= json_decode(json_encode($contents->data), true);
		$wcalendar 								= $work['calendar'];

		unset($search);
		unset($sort);

		//look for schedule based on work calendar
		$search['calendarid'] 					= $wcalendar['id'];
		$search['ondate'] 						= [$start, $end];
		$sort 									= ['on' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Schedule, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
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
		if(!is_null($work['end']))
		{
			$ended 								= new DateTime( min(date('Y-m-d', strtotime($end)), date('Y-m-d', strtotime($work['end'])) ) );
		}
		else
		{
			$ended 								= new DateTime( $end );
		}

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
		$adddate 								= [];
		$k 										= 0;
		$flag 									= 0;
		$flag2 									= 0;
		$flag_date 								= 0;

		foreach ( $periods as $period )
		{
			foreach($pschedule as $i => $sh)	
			{
				if($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])))
				{
					$schedule[$k]['id']				= $k;
					$schedule[$k]['mode_info']		= 'schedule';
					$schedule[$k]['top_title'] 		= 'Jadwal';
					$schedule[$k]['title'] 			= $sh['name'];
					$schedule[$k]['start']			= $sh['on'].'T'.$sh['start'];
					$schedule[$k]['end']			= $sh['on'].'T'.$sh['end'];
					$schedule[$k]['status']			= $sh['status'];
					$schedule[$k]['data_target']	= '#modal_schedule';
				
					if((int)Session::get('user.menuid')<4)
					{
						$schedule[$k]['ed_action']	= route('hr.person.schedules.store', ['id' => $sh['id'], 'org_id' => $org_id, 'person_id' => $person_id]);
						$schedule[$k]['del_action']	= route('hr.person.schedules.delete', ['id' => $sh['id'], 'org_id' => $org_id, 'person_id' => $person_id]);
					}	

					switch (strtolower($sh['status'])) 
					{
						case 'hb':
							$schedule[$k]['label']= 'dark-blue';
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
							$schedule[$k]['label']= 'magenta';
							break;
						default:
							$schedule[$k]['label']= 'green';
							break;
					}

					$k++;
					if(!in_array($period->format('Y-m-d'), $adddate) && (int)Session::get('user.menuid')<4)
					{
						$schedule[$k]['mode']			= 'create';
						$schedule[$k]['data_target']	= '#modal_schedule';
						$schedule[$k]['title'] 			= 'Tambah Jadwal';
						$schedule[$k]['start']			= $sh['on'];
						$schedule[$k]['end']			= $sh['on'];
						$schedule[$k]['status']			= $sh['status'];
						$schedule[$k]['add_action']		= route('hr.person.schedules.store', ['org_id' => $org_id, 'person_id' => $person_id]);
						$schedule[$k]['label']			= 'label-schedule label-primary';
						$schedule[$k]['info_default']	= 'yes';
						$adddate[]						= $period->format('Y-m-d');
						$k++;
					}
					$date[]								= $period->format('Y-m-d');
				}
			}

			if(!in_array($period->format('Y-m-d'), $date))
			{
				foreach($cschedule as $i => $sh)	
				{
					if($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])) && !in_array($period->format('Y-m-d'), $date))
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['mode_info']		= 'schedule';
						$schedule[$k]['top_title']		= 'Jadwal';
						$schedule[$k]['title'] 			= $sh['name'];
						$schedule[$k]['start']			= $sh['on'].'T'.$sh['start'];
						$schedule[$k]['end']			= $sh['on'].'T'.$sh['end'];
						$schedule[$k]['status']			= $sh['status'];

						if((int)Session::get('user.menuid')<4)
						{
							$schedule[$k]['data_target']	= '#modal_schedule';
							$schedule[$k]['ed_action']		= route('hr.person.schedules.store', ['id' => null, 'org_id' => $org_id, 'person_id' => $person_id]);
						}

						switch (strtolower($sh['status'])) 
						{
							case 'hb':
								$schedule[$k]['label']= 'dark-blue';
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
								$schedule[$k]['label']= 'magenta';
								break;
							default:
								$schedule[$k]['label']= 'green';
								break;
						}

						$k++;
						if(!in_array($period->format('Y-m-d'), $adddate) && (int)Session::get('user.menuid')<4)
						{
							$schedule[$k]['mode']			= 'create';
							$schedule[$k]['data_target']	= '#modal_schedule';
							$schedule[$k]['title'] 			= 'Tambah Jadwal';
							$schedule[$k]['start']			= $sh['on'];
							$schedule[$k]['end']			= $sh['on'];
							$schedule[$k]['status']			= $sh['status'];
							$schedule[$k]['add_action']		= route('hr.person.schedules.store', ['org_id' => $org_id, 'person_id' => $person_id]);
							$schedule[$k]['label']			= 'label-schedule label-primary';
							$schedule[$k]['info_default']	= 'yes';

							$adddate[]						= $period->format('Y-m-d');
							$k++;
						}
						$date[]								= $period->format('Y-m-d');
					}
				}

				if(!in_array($period->format('Y-m-d'), $date))
				{
					$schedule[$k]['mode']				= 'create';

					if(in_array(strtolower($period->format('l')), $workdays))
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['title'] 			= 'Masuk Kerja';
						$schedule[$k]['start']			= $period->format('Y-m-d').'T'.$wcalendar['start'];
						$schedule[$k]['end']			= $period->format('Y-m-d').'T'.$wcalendar['end'];
						$schedule[$k]['status']			= 'HB';
						$schedule[$k]['label']			= 'label-schedule label-gray';
						$schedule[$k]['info_default']	= 'yes';
						// $schedule[$k]['backgroundColor']= '#31708F';
						// $schedule[$k]['color']			= '#31708F';
						if((int)Session::get('user.menuid')<4)
						{
							$schedule[$k]['data_target']	= '#modal_schedule';
							$schedule[$k]['ed_action']		= route('hr.person.schedules.store', ['id' => null, 'org_id' => $org_id, 'person_id' => $person_id]);
						}

						$date[]							= $period->format('Y-m-d');
						$k++;
					}
					else
					{
						$schedule[$k]['id']				= $k;
						$schedule[$k]['title'] 			= 'Libur';
						$schedule[$k]['start']			= $period->format('Y-m-d').'T'.'00:00:00';
						$schedule[$k]['end']			= $period->format('Y-m-d').'T'.'00:00:00';
						$schedule[$k]['status']			= 'L';
						$schedule[$k]['label']			= 'label-schedule label-magenta';
						$schedule[$k]['info_default']	= 'yes';
						// $schedule[$k]['backgroundColor']= '#D78409';
						// $schedule[$k]['color']			= '#D78409';
						if((int)Session::get('user.menuid')<4)
						{
							$schedule[$k]['data_target']	= '#modal_schedule';
							$schedule[$k]['ed_action']		= route('hr.person.schedules.store', ['id' => null, 'org_id' => $org_id, 'person_id' => $person_id]);
						}
						$date[]							= $period->format('Y-m-d');
						$k++;
					}
				}

				// if(($period->format('Y-m-d') == date('Y-m-d', strtotime($sh['on'])))&&(!in_array($period->format('Y-m-d'), $date)))
			}
		}

		// log	
		unset($search);
		unset($sort);
		$search 								= ['personid' => $person_id, 'ondate'=> [$start, $end], 'lastattendancelog' => null];
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
			$schedule[$k]['mode']			= 'edit';
			$schedule[$k]['top_title'] 		= 'Log';
			$schedule[$k]['title'] 			= $log['attendancelogs'][0]['modified_status'] ? $log['attendancelogs'][0]['modified_status'] : $log['attendancelogs'][0]['actual_status'];
			$schedule[$k]['mode_info']		= 'log';

			if ((strtotime($log['fp_start']) < strtotime($log['fp_end'])) | (strtotime($log['fp_start']) != strtotime($log['fp_end'])))
			{
				$schedule[$k]['start']		= $log['on'].'T'.$log['fp_start'];
				$schedule[$k]['end']		= $log['on'].'T'.$log['fp_end'];
			}
			else 
			{
				$schedule[$k]['start']		= $log['on'].'T'.$log['start'];
				$schedule[$k]['end']		= $log['on'].'T'.$log['end'];
			}

			// $schedule[$k]['status']			= $log['tooltip'];
			$schedule[$k]['label']			= 'pink';
			// $schedule[$k]['backgroundColor']= '#9c27b0';
			// $schedule[$k]['mode']			= 'log';	

			if ($schedule[$k]['title']=='AS') {
				$schedule[$k]['status']	= 'Ketidakhadiran Tanpa Penjelasan';
			}
			elseif ($schedule[$k]['title']=='CB') {
				$schedule[$k]['status']	=  'Cuti Bersama';
			}
			elseif ($schedule[$k]['title']=='CI') {
				$schedule[$k]['status']	= 'Cuti Istimewa';
			}
			elseif ($schedule[$k]['title']=='CN') {
				$schedule[$k]['status']	= 'Cuti Untuk Keperluan Pribadi';
			}
			elseif ($schedule[$k]['title']=='DN') {
				$schedule[$k]['status']	= 'Keperluan Dinas';
			}
			elseif ($schedule[$k]['title']=='HC') {
				$schedule[$k]['status']	= 'Hadir Cacat Tanpa Penjelasan';
			}
			elseif ($schedule[$k]['title']=='HD') {
				$schedule[$k]['status']	= 'Hadir Cacat Dengan Ijin Dinas';
			}
			elseif ($schedule[$k]['title']=='HP') {
				$schedule[$k]['status']	= 'Hadir Cacat Dengan Ijin Pulang Cepat';
			}
			elseif ($schedule[$k]['title']=='HT') {
				$schedule[$k]['status']	=  'Hadir Cacat Dengan Ijin Datang Terlambat';
			}
			elseif ($schedule[$k]['title']=='SS') {
				$schedule[$k]['status']	= 'Sakit Jangka Pendek';
			}
			elseif ($schedule[$k]['title']=='SL') {
				$schedule[$k]['status']	= 'Sakit Berkepanjangan';
			}
			elseif ($schedule[$k]['title']=='UL') {
				$schedule[$k]['status']	= 'Ketidakhadiran Dengan Ijin Namun Cuti Tidak Tersedia';
			}

			$k++;
		}
		return Response::json($schedule);
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

			if(Input::has('person_id'))
			{
				$person_id 					= Input::get('person_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['organisationid' => $org_id, 'id' => $person_id];
			$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'personid' => $person_id];
			$results 						= $this->dispatch(new Getting(new PersonSchedule, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new PersonSchedule, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.person.schedules.index', ['org_id' => $org_id, 'person_id' => $person_id])->with('alert_success', ' Data Kerabat sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}