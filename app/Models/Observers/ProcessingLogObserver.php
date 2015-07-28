<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\ProcessLog;
use App\Models\Work;
use App\Models\Log;
use App\Models\Person;
use App\Models\SettingIdle;
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
			
			$idle_rule 				= new SettingIdle;
			$idle_rule 				= $idle_rule->organisationid($person->organisation_id)->OnDate($on)->orderBy('start', 'desc')->first();
			
			$margin_bottom_idle 	= 900;
			
			if($idle_rule)
			{
				$idle_1 			= $idle_rule->idle_1;
				$idle_2 			= $idle_rule->idle_2;
				if(isset($idle_rule->margin_bottom_idle))
				{
					$margin_bottom_idle 	= $idle_rule->margin_bottom_idle;
				}
			}
			else
			{
				$idle_1 			= 3600;
				$idle_2 			= 7200;
			}

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

			$frequency_idle_1 		= 0;
			$frequency_idle_2 		= 0;
			$frequency_idle_3 		= 0;

			$actual_status 			= '';

			$name 					= 'Attendance';
			$tooltip 				= [];

			$pschedulee 			= Person::ID($model['attributes']['person_id'])->maxendschedule(['on' => [$on, $on]])->first();
			$pschedules 			= Person::ID($model['attributes']['person_id'])->minstartschedule(['on' => [$on, $on]])->first();

			if($pschedulee && $pschedules)
			{
				$schedule_start		= $pschedules->schedules[0]->start;
				$schedule_end		= $pschedulee->schedules[0]->end;

				$working 			= Person::ID($model['attributes']['person_id'])->CurrentWork($on)->first();
				
				if(isset($working['works'][0]))
				{
					$workid 		= $working['works'][0]['pivot']['id'];
				}

				if(strtoupper($model['attributes']['name'])=='DN')
				{
					if(!in_array($model['attributes']['name'], $tooltip))
					{
						$tooltip[] 	= $model['attributes']['name'];
					}

					if(count($pschedules->schedules) > 1)
					{
						$modified_status 		= 'HD';
						$actual_status 			= 'HC';
					}
					else
					{
						$modified_status 		= 'DN';
						$actual_status 			= 'AS';
					}
					$modified_by 	= $model['attributes']['created_by'];
					$modified_at 	= $model['attributes']['created_at'];
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
					if(strtoupper($pschedules->schedules[0]->status)!='HB')
					{
						$modified_status= $pschedules->schedules[0]->status;
						$modified_by 	= $pschedules->schedules[0]->created_by;
						$modified_at 	= $pschedules->schedules[0]->created_at;
					}
				}
			}
			else
			{
				$ccalendars 		= Person::ID($model['attributes']['person_id'])->WorkCalendar(true)->WorkCalendarschedule(['on' => [$on, $on]])->first();
				if(!is_null($ccalendars))
				{
					$ccalendar 		= Person::ID($model['attributes']['person_id'])->WorkCalendar(true)->WorkCalendarschedule(['on' => [$on, $on]])->WithWorkCalendarSchedules(['on' => [$on, $on]])->first();

					$schedule_start	= $ccalendar->workscalendars[0]->calendar->schedules[0]->start;
					$schedule_end	= $ccalendar->workscalendars[0]->calendar->schedules[0]->end;
					$workid 		= $ccalendar->workscalendars[0]->id;

					//sync schedule status with process log
					switch (strtolower($ccalendar->workscalendars[0]->calendar->schedules[0]->status)) 
					{
						case 'dn':
							if(count($ccalendar->workscalendars[0]->calendar->schedules) > 1)
							{
								$modified_status 		= 'DN';
							}
							else
							{
								$modified_status 		= 'HD';
							}

							$modified_by 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_by;
							$modified_at 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_at;
							break;

						case 'ss': case 'sl' : case 'cn' : case 'ci' : case 'cb' : case 'ul' :
							$modified_status 			= strtoupper($ccalendar->workscalendars[0]->calendar->schedules[0]->status);
							$modified_by 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_by;
							$modified_at 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_at;
							break;
					}

				}
				else
				{
					$calendar 		= Person::ID($model['attributes']['person_id'])->WorkCalendar(true)->WithWorkSchedules(true)->first();

					if($calendar)
					{
						if(!isset($calendar->workscalendars[0]))
						{
							return true;
						}
						
						$workid 	= $calendar->workscalendars[0]->id;
						$workdays  	= explode(',', $calendar->workscalendars[0]->calendar->workdays);
						$lworkdays 	= [];
						foreach ($workdays as $idx => $days) 
						{
							$lworkdays[] 	= strtolower($days);
						}

						$wd			= ['monday' => 'senin', 'tuesday' => 'selasa', 'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu', 'sunday' => 'minggu', 'senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday'];
						$day 		= date("l", strtotime($model['attributes']['on']));

						if(isset($wd[strtolower($day)]) && in_array(strtolower($wd[strtolower($day)]), $lworkdays))
						{
							$schedule_start = $calendar->workscalendars[0]->calendar->start;
							$schedule_end 	= $calendar->workscalendars[0]->calendar->end;	

							//sync schedule status with process log
							switch (strtolower($calendar->workscalendars[0]->calendar->status)) 
							{
								case 'dn':
									if(count($calendar->workscalendars) > 1)
									{
										$modified_status 		= 'DN';
									}
									else
									{
										$modified_status 		= 'HD';
									}

									$modified_by 				= $calendar->workscalendars[0]->calendar->created_by;
									$modified_at 				= $calendar->workscalendars[0]->calendar->created_at;
									break;

								case 'ss': case 'sl' : case 'cn' : case 'ci' : case 'cb' : case 'ul' :
									$modified_status 			= strtoupper($calendar->workscalendars[0]->calendar->status);
									$modified_by 				= $calendar->workscalendars[0]->calendar->created_by;
									$modified_at 				= $calendar->workscalendars[0]->calendar->created_at;
									break;
							}
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

				if(isset($data->modified_by) && $data->modified_by!=0)
				{
					$modified_by 	= $data->modified_by;
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
					
					if(date('H:i:s',strtotime($data->end)) < $time)
					{
						$end 		= $time;
					}
				}
			}
			else
			{
				if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0)
				{
					$modified_by	= $model['attributes']['created_by'];
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
					$tooltip[]		= 'absencesystem';
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

			if($fp_end=='00:00:00')
			{
				$maxend 			= $end;
			}
			else
			{
				$maxend 				= max($end, $fp_end);
			}

			list($hours, $minutes, $seconds) = explode(":", $minstart);

			$minstart 				= $hours*3600+$minutes*60+$seconds;

			list($hours, $minutes, $seconds) = explode(":", $schedule_start);

			$schedule_start_second	= $hours*3600+$minutes*60+$seconds;

			$margin_start 			= $schedule_start_second - $minstart;

			list($hours, $minutes, $seconds) = explode(":", $maxend);

			$maxend 				= $hours*3600+$minutes*60+$seconds;

			list($hours, $minutes, $seconds) = explode(":", $schedule_end);

			$schedule_end_second	= $hours*3600+$minutes*60+$seconds;

			$margin_end 			= $maxend - $schedule_end_second;

			$idle 					= Log::ondate([$on, date('Y-m-d', strtotime($on.' + 1 day'))])->personid($model['attributes']['person_id'])->orderBy('on', 'asc')->get();

			$total_active 			= abs($maxend) - abs($minstart);

			foreach ($idle as $key => $value) 
			{
				//if third version
				if(isset($model['attributes']['app_version']) && (float)$model['attributes']['app_version']>=0.3)
				{
					if($key==0)
					{
						$last_input_time = date('H:i:s', strtotime('00:00:00'));
					}

					if(date('H:i:s', strtotime($value['last_input_time'])) == $last_input_time)
					{
						if(isset($start_idle))
						{
							$start_idle	= min($start_idle, date('H:i:s', strtotime($value['last_input_time'])));
						}
						else
						{
							$start_idle	= date('H:i:s', strtotime($value['last_input_time']));
						}
					}
					elseif(strtolower($value['name'])=='sessionlock' || strtolower($value['name'])=='consoledisconnect' || strtolower($value['name'])=='sessionlogout')
					{
						if(isset($start_idle))
						{
							$start_idle	= min($start_idle, date('H:i:s', strtotime($value['last_input_time'])));
						}
						else
						{
							$start_idle	= date('H:i:s', strtotime($value['last_input_time']));
						}
					}
					elseif(isset($start_idle))
					{
						$new_idle 		= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $new_idle);

						$new_idle 		= $hours*3600+$minutes*60+$seconds;

						list($hours, $minutes, $seconds) = explode(":", $start_idle);
						$start_idle 	= $hours*3600+$minutes*60+$seconds;

						$total_idle		= $total_idle + $new_idle - $start_idle;
						if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
						{
							$total_idle_1 		= $total_idle_1 + $new_idle - $start_idle;
							$frequency_idle_1 	= $frequency_idle_1 + 1;
						}
						elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
						{
							$total_idle_2 		= $total_idle_2 + $new_idle - $start_idle;
							$frequency_idle_2 	= $frequency_idle_2 + 1;
						}
						elseif($new_idle - $start_idle >= $idle_2)
						{
							$total_idle_3 		= $total_idle_3 + $new_idle - $start_idle;
							$frequency_idle_3 	= $frequency_idle_3 + 1;
						}

						unset($start_idle);
					}

					$last_input_time 		= date('H:i:s', strtotime($value['last_input_time']));
				}

				//if second version
				else
				{
					if(in_array(strtolower($value['name']), ['idle', 'sessionlock', 'sleep']) && !isset($start_idle))
					{
						$start_idle 	= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $start_idle);

						$start_idle 	= $hours*3600+$minutes*60+$seconds;
					}
					elseif(!in_array(strtolower($value['name']), ['idle', 'sessionlock', 'sleep']) && isset($start_idle))
					{
						$new_idle 		= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $new_idle);

						$new_idle 		= $hours*3600+$minutes*60+$seconds;

						$total_idle		= $total_idle + $new_idle - $start_idle;
						if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
						{
							$total_idle_1 	= $total_idle_1 + $new_idle - $start_idle;
							$frequency_idle_1 = $frequency_idle_1 + 1;
						}
						elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
						{
							$total_idle_2 	= $total_idle_2 + $new_idle - $start_idle;
							$frequency_idle_2 = $frequency_idle_2 + 1;
						}
						elseif($new_idle - $start_idle >= $idle_2)
						{
							$total_idle_3 	= $total_idle_3 + $new_idle - $start_idle;
							$frequency_idle_3 = $frequency_idle_3 + 1;
						}
						
						unset($start_idle);
					}

					if(strtolower($value['name']) == 'sleep')
					{
						$start_sleep 	= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $start_sleep);

						$start_sleep 	= $hours*3600+$minutes*60+$seconds;
					}
					elseif((strtolower($value['name']) != 'sleep') && isset($start_sleep))
					{
						$new_sleep 		= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $new_sleep);

						$new_sleep 		= $hours*3600+$minutes*60+$seconds;

						$total_sleep	= $total_sleep + $new_sleep - $start_sleep;
						unset($start_sleep);
					}
				}
			}

			if(isset($start_idle))
			{
				$new_idle 		= date('H:i:s', strtotime($value['on']));
				list($hours, $minutes, $seconds) = explode(":", $new_idle);

				$new_idle 		= $hours*3600+$minutes*60+$seconds;

				list($hours, $minutes, $seconds) = explode(":", $start_idle);
				$start_idle 	= $hours*3600+$minutes*60+$seconds;

				$total_idle		= $total_idle + $new_idle - $start_idle;
				if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
				{
					$total_idle_1 		= $total_idle_1 + $new_idle - $start_idle;
					$frequency_idle_1 	= $frequency_idle_1 + 1;
				}
				elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
				{
					$total_idle_2 		= $total_idle_2 + $new_idle - $start_idle;
					$frequency_idle_2 	= $frequency_idle_2 + 1;
				}
				elseif($new_idle - $start_idle >= $idle_2)
				{
					$total_idle_3 		= $total_idle_3 + $new_idle - $start_idle;
					$frequency_idle_3 	= $frequency_idle_3 + 1;
				}

				unset($start_idle);
			}

			if(!$actual_status!='' && $margin_start==0 && $margin_end==0)
			{
				$actual_status 			= 'AS';
			}
			elseif(!$actual_status!='' && $margin_start>=0 && $margin_end>=0)
			{
				$actual_status 			= 'HB';
			}
			elseif(!$actual_status!='')
			{
				$actual_status 			= 'HC';
			}

			$total_active 				= abs($total_active) - abs($total_sleep) - abs($total_idle);

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
									'actual_status'			=> $actual_status,
									'frequency_idle_1'		=> $frequency_idle_1,
									'frequency_idle_2'		=> $frequency_idle_2,
									'frequency_idle_3'		=> $frequency_idle_3,
							]
					);

			if(isset($modified_by))
			{
				$plog->fill(['modified_by' 					=> $modified_by]);
			}
			else
			{
				unset($plog->modified_by);
			}

			if(isset($modified_status))
			{
				$plog->fill(['modified_status' 				=> $modified_status]);
			}

			if(isset($modified_at))
			{
				$plog->fill(['modified_at' 					=> $modified_at]);
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
