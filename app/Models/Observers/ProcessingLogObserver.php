<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\ProcessLog;
use App\Models\Work;
use App\Models\Log;
use App\Models\Person;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saved						
 * ---------------------------------------------------------------------- */

class ProcessingLogObserver 
{
	public function saved($model)
	{
		if(isset($model['attributes']['person_id']))
		{
			$this->errors 			= new MessageBag;

			$on 					= date("Y-m-d", strtotime($model['attributes']['on']));
			$time 					= date("H:i:s", strtotime($model['attributes']['on']));

			$person 				= Person::find($model['attributes']['person_id']);
			$workid 				= null;
			$plog 					= new ProcessLog;
			$data 					= $plog->ondate([$on, $on])->personid($model['attributes']['person_id'])->first();

			$fp_start 				= '00:00:00';
			$fp_end 				= '00:00:00';
			$start 					= '00:00:00';
			$end 					= '00:00:00';
			$schedule_start 		= '00:00:00';
			$schedule_end 			= '00:00:00';

			$margin_start 			= 0;
			$margin_end 			= 0;
			$total_idle 			= 0;
			$total_idle_1 			= 0;
			$total_idle_2 			= 0;
			$total_idle_3 			= 0;
			$total_sleep 			= 0;
			$total_active 			= 0;

			$actual_start_status 	= '';
			$actual_end_status 		= '';

			$name 					= 'Attendance';
			$tooltip 				= [];

			$pschedulee 			= Person::ID($model['attributes']['person_id'])->maxendschedule(['on' => [$on, $on]])->first();
			$pschedules 			= Person::ID($model['attributes']['person_id'])->minstartschedule(['on' => [$on, $on]])->first();
			if($pschedulee && $pschedules)
			{
				$schedule_start		= $pschedules->schedules[0]->start;
				$schedule_end		= $pschedulee->schedules[0]->end;
				if($model['attributes']['name']=='presence_outdoor')
				{
					if(!in_array($model['attributes']['name'], $tooltip))
					{
						$tooltip[] 	= $model['attributes']['name'];
					}						
				}
				else
				{
					foreach($pschedules->schedules as $key => $value)
					{
						if(!in_array($value->status, $tooltip))
						{
							$tooltip[] 	= $value->status;
						}
					}
				}
			}
			else
			{
				$ccalendars 		= Person::ID($model['attributes']['person_id'])->CheckWork(true)->WorkCalendar(true)->WorkCalendarschedule(['on' => [$on, $on]])->first();
				if(!is_null($ccalendars))
				{
					$ccalendar 		= Person::ID($model['attributes']['person_id'])->CheckWork(true)->WorkCalendar(true)->WorkCalendarschedule(['on' => [$on, $on]])->with(['workscalendars', 'workscalendars.calendar', 'workscalendars.calendar.schedules' => function($q)use($on){$q->ondate([$on, $on]);}])->first();
					
					$schedule_start	= $ccalendar->workscalendars[0]->calendar->schedules[0]->start;
					$schedule_end	= $ccalendar->workscalendars[0]->calendar->schedules[0]->end;
					$workid 		= $ccalendar->workscalendars[0]->id;

				}
				else
				{
					$calendar 		= Person::ID($model['attributes']['person_id'])->CheckWork(true)->WorkCalendar(true)->withAttributes(['workscalendars','workscalendars.calendar'])->first();
					if($calendar)
					{
						$workid 	= $calendar->workscalendars[0]->id;
						$workdays  	= explode(',', $calendar->workscalendars[0]->calendar->workdays);
						$wd			= ['monday' => 'senin', 'tuesday' => 'selasa', 'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu', 'sunday' => 'minggu'];
						$day 		= date("l", strtotime($model['attributes']['on']));

						if(isset($wd[strtolower($day)]) && in_array($wd[strtolower($day)], $workdays))
						{
							$schedule_start = $calendar->workscalendars[0]->calendar->start;
							$schedule_end 	= $calendar->workscalendars[0]->calendar->end;	
						}
						else
						{
							$schedule_start = '00:00:00';
							$schedule_end 	= '00:00:00';
						}
					}
					else
					{
						$schedule_start = '00:00:00';
						$schedule_end 	= '00:00:00';
					}
				}
			}

			if(isset($data->id))
			{
				$plog 				= $data;
				$fp_start 			= $data->fp_start;
				$fp_end 			= $data->fp_end;
				$start 				= $data->start;
				$end 				= $data->end;

				if(isset($data->created_by) && $data->created_by!=0)
				{
					$modified_end_by= $data->created_by;
				}

				$result 			= json_decode($data->tooltip);
				$tooltip 			= json_decode(json_encode($result), true);

				if(strtolower($model['attributes']['name'])=='finger_print')
				{
					if(date('H:i:s',strtotime($data->fp_start)) < $time && $data->fp_start == '00:00:00')
					{
						$fp_start 	= $time;
					}
					elseif(date('H:i:s',strtotime($data->fp_start)) > $time)
					{
						$fp_start 	= $time;
					}
					elseif(date('H:i:s',strtotime($data->fp_end)) < $time)
					{
						$fp_end 	= $time;
					}
				}
				else
				{
					if(date('H:i:s',strtotime($data->start)) < $time && $data->start == '00:00:00')
					{
						$start 		= $time;
					}
					elseif(date('H:i:s',strtotime($data->start)) > $time)
					{
						$start 		= $time;
					}
					elseif(date('H:i:s',strtotime($data->end)) < $time)
					{
						$fp_end 	= $time;
					}
				}
			}
			else
			{
				if(isset($data->created_by) && $data->created_by!=0)
				{
					$modified_start_by= $data->created_by;
				}

				if(strtolower($model['attributes']['name'])=='finger_print')
				{
					$fp_start 		= $time;
					$start 			= $time;
					$end 			= $time;
					$tooltip[]		= 'finger_print';
				}
				else
				{
					$start 			= $time;
					$end 			= $time;
					$tooltip[]		= 'tracker';
				}
			}

			if($fp_start=='00:00:00')
			{
				$minstart 			= $start;
			}
			else
			{
				$minstart 			= min($start, $fp_start);
			}

			$maxend 				= max($end, $fp_end);

			list($hours, $minutes, $seconds) = explode(":", $minstart);

			$minstart 				= $hours*3600+$minutes*60+$seconds;

			list($hours, $minutes, $seconds) = explode(":", $schedule_start);

			$schedule_start_second	= $hours*3600+$minutes*60+$seconds;

			$margin_start 			= $schedule_start_second - $minstart;

			list($hours, $minutes, $seconds) = explode(":", $maxend);

			$maxend 				= $hours*3600+$minutes*60+$seconds;

			list($hours, $minutes, $seconds) = explode(":", $schedule_end);

			$schedule_end_second	= $hours*3600+$minutes*60+$seconds;

			$margin_end 			= $schedule_end_second - $schedule_end;

			$idle 					= Log::ondate($on)->personid($model['attributes']['person_id'])->orderBy('on', 'asc')->get();
			$total_active 			= $maxend - $minstart;

			foreach ($idle as $key => $value) 
			{
				if(strtolower($value['name']) == 'idle')
				{
					$start_idle 	= date('H:i:s', strtotime($value['on']));
					list($hours, $minutes, $seconds) = explode(":", $start_idle);

					$start_idle 	= $hours*3600+$minutes*60+$seconds;
				}
				elseif(strtolower($value['name'] != 'idle') && isset($start_idle))
				{
					$new_idle 		= date('H:i:s', strtotime($value['on']));
					list($hours, $minutes, $seconds) = explode(":", $new_idle);

					$new_idle 		= $hours*3600+$minutes*60+$seconds;

					$total_idle		= $total_idle + $new_idle - $start_idle;
					if($new_idle - $start_idle <= 900)
					{
						$total_idle_1 	= $total_idle_1 + $new_idle - $start_idle;
					}
					elseif($new_idle - $start_idle > 900 && $new_idle - $start_idle < 3600)
					{
						$total_idle_2 	= $total_idle_2 + $new_idle - $start_idle;
					}
					elseif($new_idle - $start_idle >= 3600)
					{
						$total_idle_3 	= $total_idle_3 + $new_idle - $start_idle;
					}
					
					unset($start_idle);
				}

				if(strtolower($value['name']) == 'sleep')
				{
					$start_sleep 	= date('H:i:s', strtotime($value['on']));
					list($hours, $minutes, $seconds) = explode(":", $start_sleep);

					$start_sleep 	= $hours*3600+$minutes*60+$seconds;
				}
				elseif(strtolower($value['name'] != 'sleep') && isset($start_sleep))
				{
					$new_sleep 		= date('H:i:s', strtotime($value['on']));
					list($hours, $minutes, $seconds) = explode(":", $new_sleep);

					$new_sleep 		= $hours*3600+$minutes*60+$seconds;

					$total_sleep	= $total_sleep + $new_sleep - $start_sleep;
					unset($start_sleep);
				}
			}

			if($margin_start==0 && $margin_end==0)
			{
				$actual_start_status 	= 'AS';
			}
			elseif($margin_start>=0)
			{
				$actual_start_status 	= 'HB';
			}
			else
			{
				$actual_start_status 	= 'HC';
			}
			
			if($margin_start==0 && $margin_end==0)
			{
				$actual_end_status 		= 'AS';
			}
			elseif($margin_end>=0)
			{
				$actual_end_status 		= 'HB';
			}
			else
			{
				$actual_end_status 		= 'HC';
			}

			$total_active 				= $total_active - $total_sleep - $total_idle;

			$plog->fill([
										'name'					=> $name,
										'on'					=> $on,
										'schedule_start'		=> $schedule_start,
										'schedule_end'			=> $schedule_end,										
										'tooltip'				=> json_encode($tooltip),
										'start'					=> $start,
										'end'					=> $end,
										'fp_start'				=> $fp_start,
										'fp_end'				=> $fp_end,
										'margin_start'			=> $margin_start,
										'margin_end'			=> $margin_end,
										'total_idle'			=> $total_idle,
										'total_idle_1'			=> $total_idle_1,
										'total_idle_2'			=> $total_idle_2,
										'total_idle_3'			=> $total_idle_3,
										'total_sleep'			=> $total_sleep,
										'total_active'			=> $total_active,
										'actual_start_status'	=> $actual_start_status,
										'actual_end_status'		=> $actual_end_status,
								]
						);

			if(isset($modified_start_by))
			{
				$plog->fill(['modified_start_by' 	=> $modified_start_by]);
			}

			if(isset($modified_end_by))
			{
				$plog->fill(['modified_end_by' 		=> $modified_end_by]);
			}

			$plog->Person()->associate($person);

			if(!is_null($workid))
			{
				$work 			= Work::find($workid);
				if($work)
				{
					$plog->Work()->associate($work);
				} 		
			}

			if (!$plog->save())
			{
				$model['errors'] = $plog->getError();
				return false;
			}

			return true;
		}
		return true;
	}
}
