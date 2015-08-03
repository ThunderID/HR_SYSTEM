<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\PersonSchedule;
use App\Models\ProcessLog;
use App\Models\Person;
use App\Models\Log;
use App\Models\Work;
use App\Models\PersonWorkleave;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonScheduleObserver 
{
	public function saving($model)
	{
		$validator 							= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 				= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		$errors 							= new MessageBag;
		if(date('Y-m-d', strtotime($model['attributes']['on']))<=date('Y-m-d') && isset($model['attributes']['person_id']) && $model['attributes']['person_id'] != 0)
		{
			$processlogs 					= ProcessLog::ondate([date('Y-m-d', strtotime($model['attributes']['on'])), date('Y-m-d', strtotime($model['attributes']['on']))])->personid($model['attributes']['person_id'])->get();
			if($processlogs->count())
			{
				foreach ($processlogs as $key => $value) 
				{
					$data					= ProcessLog::ID($value->id)->first();
					$on 					= date('Y-m-d', strtotime($model['attributes']['on']));

					$result 				= json_decode($data->tooltip);
					$tooltip 				= json_decode(json_encode($result), true);

					$pschedulee 			= Person::ID($model['attributes']['person_id'])->maxendschedule(['on' => [$on, $on]])->first();
					$pschedules 			= Person::ID($model['attributes']['person_id'])->minstartschedule(['on' => [$on, $on]])->first();
				
					if($pschedulee && $pschedules)
					{
						$schedule_start		= $pschedules->schedules[0]->start;
						$schedule_end		= $pschedulee->schedules[0]->end;
						if(strtoupper($model['attributes']['status'])=='DN')
						{
							if(!in_array($model['attributes']['status'], $tooltip))
							{
								$tooltip[] 		= $model['attributes']['status'];
							}						
						}
						else
						{
							foreach($pschedules->schedules as $key => $value)
							{
								if(!in_array($value->status, $tooltip))
								{
									$tooltip[] 		= $value->status;
								}
							}
						}
					}
					else
					{
						$schedule_end		= $data->schedule_start;
						$schedule_start 	= $data->schedule_end;
					}

					//hitung margin start
					list($hours, $minutes, $seconds) = explode(":", $schedule_start);

					$schedule_start			= $hours*3600+$minutes*60+$seconds;

					$start 					= $data->start;
					list($hours, $minutes, $seconds) = explode(":", $start);

					$start 					= $hours*3600+$minutes*60+$seconds;

					$margin_start			= $schedule_start - $start;

					//hitung margin end
					list($hours, $minutes, $seconds) = explode(":", $schedule_end);

					$schedule_end			= $hours*3600+$minutes*60+$seconds;

					$end 					= $data->end;
					list($hours, $minutes, $seconds) = explode(":", $end);

					$end 					= $hours*3600+$minutes*60+$seconds;

					$margin_end				= $schedule_end - $end;

					$data->fill(['modified_at' => date('Y-m-d H:i:s', strtotime('now')), 'modified_by' => $model['attributes']['created_by'], 'modified_status' => 'DN', 'schedule_start' => gmdate('H:i:s', $schedule_start), 'schedule_end' => gmdate('H:i:s', $schedule_end), 'margin_end' => $margin_end, 'margin_start' => $margin_start, 'tooltip' => json_encode($tooltip)]);
					if(!$data->save())
					{
						$model['errors']	= $data->getError();
						return false;
					}
				}
			}
		}

		//cek pengambilan cuti
		if((strtoupper($model['attributes']['status'])=='DN' || strtoupper($model['attributes']['status'])=='CN' || strtoupper($model['attributes']['status'])=='CB' || strtoupper($model['attributes']['status'])=='CI'  || strtoupper($model['attributes']['status'])=='UL'  || strtoupper($model['attributes']['status'])=='SS' || strtoupper($model['attributes']['status'])=='SL' || strtoupper($model['attributes']['status'])=='UL') && isset($model['attributes']['person_id']))
		{
			//check if log on that day were provided
			$logged 						= Log::personid($model['attributes']['person_id'])->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->Name(strtoupper($model['attributes']['status']))->first();
			if(!$logged)
			{
				$person 					= Person::find($model['attributes']['person_id']);
				$log 						= new Log;
				$log->fill([
							'name' 			=> strtoupper($model['attributes']['status']),
							'on' 			=> date('Y-m-d H:i:s', strtotime($model['attributes']['on'].' '.$model['attributes']['start'])),
							'pc' 			=> 'hr',
							'created_by' 	=> $model['attributes']['created_by'],
				]);

				$log->Person()->associate($person);

				if(!$log->save())
				{

					$model['errors'] 		= $log->getError();

					return false;
				}

				$log 						= new Log;
				$log->fill([
							'name' 			=> strtoupper($model['attributes']['status']),
							'on' 			=> date('Y-m-d H:i:s', strtotime($model['attributes']['on'].' '.$model['attributes']['end'])),
							'pc' 			=> 'hr',
							'created_by' 	=> $model['attributes']['created_by'],
				]);

				$log->Person()->associate($person);

				if(!$log->save())
				{
					$model['errors'] 		= $log->getError();

					return false;
				}
			}

			
			if(in_array(strtoupper($model['attributes']['status']),['CN','CB']))
			{
				$workid 					= Work::personid($model['attributes']['person_id'])->active(true)->wherehas('calendar', function($q){$q;})->orderBy('end', 'desc')->first();
				//check if pw on that day were provided
				$pwM 						= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([date('Y-m-d', strtotime($model['attributes']['on'])), date('Y-m-d', strtotime($model['attributes']['on']))])->status('CN')->quota(false)->first();
				if($pwM)
				{
					return true;
				}
				//check if pw on that day were not provided
				else
				{
					$pwP 					= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([date('Y-m-d', strtotime($model['attributes']['on'])), null])->status('CN')->quota(true)->first();
					if(!$pwP)
					{
						$errors->add('Workleave', 'Quota Cuti Tidak Tersedia. Jika ingin menambahkan hutang cuti, silahkan tambahkan quota cuti tahun depan.');
						$model['errors'] 	= $errors;

						return false;
					}

					$person 				= Person::find($model['attributes']['person_id']);

					$pworkleave 			= new PersonWorkleave;
					$pworkleave->fill([
							'work_id'				=> $workid->id,
							'person_workleave_id'	=> $pwP->id,
							'created_by'			=> $model['attributes']['created_by'],
							'name'					=> 'Pengambilan '.$pwP->name,
							'status'				=> $model['attributes']['status'],
							'notes'					=> $model['attributes']['name'].'<br/>Auto generated dari schedule.',
							'start'					=> $model['attributes']['on'],
							'end'					=> $model['attributes']['on'],
							'quota'					=> -1
					]);

					$pworkleave->Person()->associate($person);

					if(!$pworkleave->save())
					{
						$model['errors'] 	= $pworkleave->getError();

						return false;
					}
				}
			}
		}
	}

	public function deleting($model)
	{
		// if(date('Y-m-d',strtotime($model['attributes']['on'])) <= date('Y-m-d'))
		// {
		// 	$model['errors'] 				= ['Tidak dapat menghapus jadwal pribadi seseorang pada waktu yang telah lewat atau hari ini. Silahkan tambahkan jadwal perorangan yang baru.'];

		// 	return false;
		// }

		$logs 								= Log::personid($model['attributes']['person_id'])->ondate([date('Y-m-d',strtotime($model['attributes']['on'])), date('Y-m-d',strtotime($model['attributes']['on'].' + 1 Day'))])->get();

		foreach ($logs as $key => $value) 
		{
			$log 							= Log::find($value->id);

			if(!$log->delete())
			{
				$model['errors'] 			= $log->getError();
				
				return false;
			}
		}
	}

	public function deleted($model)
	{
		$plog 								= ProcessLog::personid($model['attributes']['person_id'])->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->modifiedstatus(strtoupper($model['attributes']['status']))->get();
		if($plog->count())
		{
			foreach ($plog as $key => $value) 
			{
				$dplog 						= ProcessLog::find($value->id);
				if(!$dplog->delete())
				{
					$model['errors']		= $dplog->getError();
				
					return false;
				}
			}
		}

		return true;
	}
}
