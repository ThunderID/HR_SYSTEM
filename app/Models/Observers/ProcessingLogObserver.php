<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\ProcessLog;
use App\Models\Work;
use App\Models\Log;
use App\Models\Person;
use App\Models\Policy;
use App\Models\AttendanceLog;
use App\Models\IdleLog;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saved						
 * ---------------------------------------------------------------------- */

class ProcessingLogObserver 
{
	public function saved($model)
	{
		set_time_limit(0);
		
		if(isset($model['attributes']['person_id']))
		{
			//preparing usable variable
			$this->errors 			= new MessageBag;

			$on 					= date("Y-m-d", strtotime($model['attributes']['on']));
			$time 					= date("H:i:s", strtotime($model['attributes']['on']));

			if(isset($model['attributes']['app_version']) && (float)$model['attributes']['app_version']>=0.3)
			{
				$ltime 				= date("H:i:s", strtotime($model['attributes']['last_input_time']));
				$lon 				= date("Y-m-d", strtotime($model['attributes']['last_input_time']));
			}
			else
			{
				$lon 				= $on;
				$ltime 				= $time;
			}

			//find person
			$person 				= Person::find($model['attributes']['person_id']);
			
			//check idle rule in policy (if doesn't exists use default)
			$idle_rule 				= new Policy;
			$idle_rule_1 			= $idle_rule->organisationid($person->organisation_id)->type('firstidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			$idle_rule_2 			= $idle_rule->organisationid($person->organisation_id)->type('secondidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			$idle_rule_3 			= $idle_rule->organisationid($person->organisation_id)->type('thirdidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			
			$margin_bottom_idle 	= 900;
			$idle_1 				= 3600;
			$idle_2 				= 7200;
			
			if($idle_rule_1)
			{
				$margin_bottom_idle = (int)$idle_rule_1->value;
			}
			if($idle_rule_2)
			{
				$idle_1 			= (int)$idle_rule_2->value;
			}
			if($idle_rule_3)
			{
				$idle_2 			= (int)$idle_rule_3->value;
			}

			$workid 				= null;
			$is_absence 			= 0;
			$plog 					= new ProcessLog;

			//check if process log exists
			$data 					= $plog->ondate([$on, $on])->personid($model['attributes']['person_id'])->first();

			//preparing default variable
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
			$break_idle 			= 0;
			$total_active 			= 0;
			$count_status 			= 1;
			$break_time 			= 0;
			$idle_line 				= '';

			$frequency_idle_1 		= 0;
			$frequency_idle_2 		= 0;
			$frequency_idle_3 		= 0;

			$actual_status 			= '';

			$name 					= 'Attendance';
			$tooltip 				= [];

			//1. CHECK SCHEDULE
			//check if person schedule were provided
			$pschedulee 			= Person::ID($model['attributes']['person_id'])->maxendschedule(['on' => [$on, $on]])->first();
			$pschedules 			= Person::ID($model['attributes']['person_id'])->minstartschedule(['on' => [$on, $on]])->first();

			if($pschedulee && $pschedules)
			{
				$schedule_start		= $pschedules->schedules[0]->start;
				
				$schedule_end		= $pschedulee->schedules[0]->end;

				$break_idle			= $pschedulee->schedules[0]->break_idle;

				//set workid
				$working 			= Person::ID($model['attributes']['person_id'])->CurrentWork($on)->first();
				
				if(isset($working['works'][0]))
				{
					$workid 		= $working['works'][0]['pivot']['id'];
					$is_absence 	= $working['works'][0]['pivot']['is_absence'];
				}

				//set modified status
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
					$modified_by 		= $model['attributes']['created_by'];
					$modified_at 		= $model->created_at->format('Y-m-d H:i:s');
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
					if(strtoupper($pschedules->schedules[0]->status)=='L')
					{
						$actual_status 	= 'L';
						$modified_status= $pschedules->schedules[0]->status;
						$modified_by 	= $pschedules->schedules[0]->created_by;
						$modified_at 	= $pschedules->schedules[0]->created_at->format('Y-m-d H:i:s');
					}
					elseif(in_array(strtoupper($pschedules->schedules[0]->status),['CN', 'CB', 'CI']))
					{
						$actual_status 	= 'AS';
						$modified_status= $pschedules->schedules[0]->status;
						$modified_by 	= $pschedules->schedules[0]->created_by;
						$modified_at 	= $pschedules->schedules[0]->created_at->format('Y-m-d H:i:s');
					}
					elseif(strtoupper($pschedules->schedules[0]->status)!='HB')
					{
						$actual_status 	= 'HC';
						$modified_status= $pschedules->schedules[0]->status;
						$modified_by 	= $pschedules->schedules[0]->created_by;
						$modified_at 	= $pschedules->schedules[0]->created_at->format('Y-m-d H:i:s');
					}
				}
			}
			else
			{
				//check if global schedule were provided
				$ccalendars 		= Person::ID($model['attributes']['person_id'])->WorkCalendar($on)->WorkCalendarschedule(['on' => [$on, $on]])->first();
				if(!is_null($ccalendars))
				{
					$ccalendar 		= Person::ID($model['attributes']['person_id'])->WorkCalendar($on)->WorkCalendarschedule(['on' => [$on, $on]])->WithWorkCalendarSchedules(['on' => [$on, $on]])->first();

					$schedule_start	= $ccalendar->workscalendars[0]->calendar->schedules[0]->start;
					$schedule_end	= $ccalendar->workscalendars[0]->calendar->schedules[0]->end;
					$break_idle		= $ccalendar->workscalendars[0]->calendar->schedules[0]->break_idle;
					$workid 		= $ccalendar->workscalendars[0]->id;
					$is_absence 	= $ccalendar->workscalendars[0]->is_absence;

					//sync schedule status with process log
					switch (strtolower($ccalendar->workscalendars[0]->calendar->schedules[0]->status)) 
					{
						case 'dn':
							if(count($ccalendar->workscalendars[0]->calendar->schedules) > 1)
							{
								$modified_status 		= 'DN';
								$actual_status 			= 'AS';
							}
							else
							{
								$modified_status 		= 'HD';
								$actual_status 			= 'HC';
							}

							$modified_by 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_by;
							$modified_at 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_at->format('Y-m-d H:i:s');
							break;

						case 'ss': case 'sl' : case 'cn' : case 'ci' : case 'cb' : case 'ul' :
							$actual_status 				= 'AS';
							$modified_status 			= strtoupper($ccalendar->workscalendars[0]->calendar->schedules[0]->status);
							$modified_by 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_by;
							$modified_at 				= $ccalendar->workscalendars[0]->calendar->schedules[0]->created_at->format('Y-m-d H:i:s');
							break;

						case 'l': 
							$actual_status 				= 'L';
							break;
					}
				}
				else
				{
					//check if default workdays provided
					$calendar 		= Person::ID($model['attributes']['person_id'])->WorkCalendar(true)->WithWorkSchedules(true)->first();

					if($calendar)
					{
						$workid 	= $calendar->workscalendars[0]->id;
						$is_absence = $calendar->workscalendars[0]->is_absence;
						$workdays  	= explode(',', $calendar->workscalendars[0]->calendar->workdays);
						$breakidles = explode(',', $calendar->workscalendars[0]->calendar->break_idle);
						$lworkdays 	= [];
						foreach ($workdays as $idx => $days) 
						{
							$lworkdays[] 					= strtolower($days);
							if($breakidles[$idx])
							{
								$lbreaks[strtolower($days)] = $breakidles[$idx];
							}
						}

						$wd			= ['monday' => 'senin', 'tuesday' => 'selasa', 'wednesday' => 'rabu', 'thursday' => 'kamis', 'friday' => 'jumat', 'saturday' => 'sabtu', 'sunday' => 'minggu', 'senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday'];
						$day 		= date("l", strtotime($model['attributes']['on']));

						if(isset($wd[strtolower($day)]) && in_array(strtolower($wd[strtolower($day)]), $lworkdays) && isset($lbreaks[strtolower($day)]))
						{
							$modified_status= '';
							$schedule_start = $calendar->workscalendars[0]->calendar->start;
							$schedule_end 	= $calendar->workscalendars[0]->calendar->end;	
							$break_idle 	= (int)$lbreaks[strtolower($day)]	
						}
						else
						{
							$actual_status 	= 'L';
							$schedule_start = '00:00:00';
							$schedule_end 	= '00:00:00';
						}
					}
					else
					{
						$actual_status 		= 'L';
						$schedule_start 	= '00:00:00';
						$schedule_end 		= '00:00:00';
					}
				}
			}

			//2. CHECK START AND END OF ACTIVITIES
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

				if(strtolower($model['attributes']['name'])=='fingerprint')
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

				if(strtolower($model['attributes']['name'])=='fingerprint')
				{
					$fp_start 		= $time;
					$start 			= $time;
					$end 			= $time;
					$tooltip[]		= 'fingerprint';
				}
				else
				{
					$start 			= $time;
					$end 			= $time;
					$tooltip[]		= 'absencesystem';
				}
			}

			if($lon < $on)
			{
				$start 				= '00:00:00';
				$end 				= '00:00:00';
				$tooltip[]			= 'sincedaysbefore';
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
				$maxend 			= max($end, $fp_end);
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

			$total_active 			= abs($maxend) - abs($minstart);

			//3. COUNT IDLE
			$idle 					= Log::ondate([$on, date('Y-m-d', strtotime($on.' + 1 day'))])->where('on', 'not like', '% 00:00:00')->personid($model['attributes']['person_id'])->orderBy('on', 'asc')->get();

			foreach ($idle as $key => $value) 
			{
				//if third version
				if(isset($model['attributes']['app_version']) && (float)$model['attributes']['app_version']>=0.3)
				{
					if($key==0)
					{
						$last_input_time 		= date('H:i:s', strtotime('00:00:00'));
					}

					if(date('H:i:s', strtotime($value['last_input_time'])) == $last_input_time)
					{
						if(isset($start_idle))
						{
							$start_idle			= min($start_idle, date('H:i:s', strtotime($value['last_input_time'])));
						}
						else
						{
							$start_idle			= date('H:i:s', strtotime($value['last_input_time']));
						}
					}
					elseif(strtolower($value['name'])=='sessionlock' || strtolower($value['name'])=='consoledisconnect' || strtolower($value['name'])=='sessionlogout')
					{
						if(isset($start_idle))
						{
							$start_idle			= min($start_idle, date('H:i:s', strtotime($value['last_input_time'])));
						}
						else
						{
							$start_idle			= date('H:i:s', strtotime($value['last_input_time']));
						}
					}
					elseif(isset($start_idle))
					{
						$new_idle 				= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $new_idle);

						$new_idle 				= $hours*3600+$minutes*60+$seconds;

						list($hours, $minutes, $seconds) = explode(":", $start_idle);
						$start_idle 			= $hours*3600+$minutes*60+$seconds;

						$total_idle				= $total_idle + $new_idle - $start_idle;
						if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
						{
							$total_idle_1 		= $total_idle_1 + $new_idle - $start_idle;
							$frequency_idle_1 	= $frequency_idle_1 + 1;
							$idlesline[]		= 'total_idle_1';
							$idles[]			= $new_idle - $start_idle;
						}
						elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
						{
							$total_idle_2 		= $total_idle_2 + $new_idle - $start_idle;
							$frequency_idle_2 	= $frequency_idle_2 + 1;
							$idlesline[]		= 'total_idle_2';
							$idles[]			= $new_idle - $start_idle;
						}
						elseif($new_idle - $start_idle >= $idle_2)
						{
							$total_idle_3 		= $total_idle_3 + $new_idle - $start_idle;
							$frequency_idle_3 	= $frequency_idle_3 + 1;
							$idlesline[]		= 'total_idle_3';
							$idles[]			= $new_idle - $start_idle;
						}

						unset($start_idle);
					}

					$last_input_time 			= date('H:i:s', strtotime($value['last_input_time']));
				}

				//if second version
				else
				{
					if(in_array(strtolower($value['name']), ['idle', 'sessionlock', 'sleep']) && !isset($start_idle))
					{
						$start_idle 			= date('H:i:s', strtotime($value['on']));
					}
					elseif(!in_array(strtolower($value['name']), ['idle', 'sessionlock', 'sleep']) && isset($start_idle))
					{
						$new_idle 				= date('H:i:s', strtotime($value['on']));
						list($hours, $minutes, $seconds) = explode(":", $new_idle);

						$new_idle 				= $hours*3600+$minutes*60+$seconds;

						list($hours, $minutes, $seconds) = explode(":", $start_idle);

						$start_idle 			= $hours*3600+$minutes*60+$seconds;

						$total_idle				= $total_idle + $new_idle - $start_idle;
						if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
						{
							$total_idle_1 		= $total_idle_1 + $new_idle - $start_idle;
							$frequency_idle_1 	= $frequency_idle_1 + 1;
							$idlesline[]		= 'total_idle_1';
							$idles[]			= $new_idle - $start_idle;
						}
						elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
						{
							$total_idle_2 		= $total_idle_2 + $new_idle - $start_idle;
							$frequency_idle_2 	= $frequency_idle_2 + 1;
							$idlesline[]		= 'total_idle_2';
							$idles[]			= $new_idle - $start_idle;
						}
						elseif($new_idle - $start_idle >= $idle_2)
						{
							$total_idle_3 		= $total_idle_3 + $new_idle - $start_idle;
							$frequency_idle_3 	= $frequency_idle_3 + 1;
							$idlesline[]		= 'total_idle_3';
							$idles[]			= $new_idle - $start_idle;
						}
						
						unset($start_idle);
					}
				}
			}

			if(isset($start_idle))
			{
				if(isset($value['on']))
				{
					$new_idle 			= date('H:i:s', strtotime($value['on']));
				}
				else
				{
					$new_idle 			= date('H:i:s', strtotime($model['attributes']['on']));
				}

				list($hours, $minutes, $seconds) = explode(":", $new_idle);

				$new_idle 				= $hours*3600+$minutes*60+$seconds;

				list($hours, $minutes, $seconds) = explode(":", $start_idle);
				$start_idle 			= $hours*3600+$minutes*60+$seconds;

				$total_idle				= $total_idle + $new_idle - $start_idle;
				if($new_idle - $start_idle >= $margin_bottom_idle && $new_idle - $start_idle <= $idle_1)
				{
					$total_idle_1 		= $total_idle_1 + $new_idle - $start_idle;
					$frequency_idle_1 	= $frequency_idle_1 + 1;
					$idlesline[]		= 'total_idle_1';
					$idles[]			= $new_idle - $start_idle;
				}
				elseif($new_idle - $start_idle > $idle_1 && $new_idle - $start_idle < $idle_2)
				{
					$total_idle_2 		= $total_idle_2 + $new_idle - $start_idle;
					$frequency_idle_2 	= $frequency_idle_2 + 1;
					$idlesline[]		= 'total_idle_2';
					$idles[]			= $new_idle - $start_idle;
				}
				elseif($new_idle - $start_idle >= $idle_2)
				{
					$total_idle_3 		= $total_idle_3 + $new_idle - $start_idle;
					$frequency_idle_3 	= $frequency_idle_3 + 1;
					$idlesline[]		= 'total_idle_3';
					$idles[]			= $new_idle - $start_idle;
				}

				unset($start_idle);
			}

			// count breaktime
			foreach ($idles as $key => $value) 
			{
				if($break_time < $value)
				{
					$break_time 		= $value;
					$idle_line 			= $idlesline[$key];
				}
			}

			$left_idle 					= $break_time - $break_idle;
			if($left_idle <= 0)
			{
				switch ($idle_line) 
				{
					case 'total_idle_1':
						$total_idle_1 		= $total_idle_1 - $break_time;
						$frequency_idle_1 	= $frequency_idle_1 - 1;
						break;
					case 'total_idle_2':
						$total_idle_2 		= $total_idle_2 - $break_time;
						$frequency_idle_2 	= $frequency_idle_2 - 1;
						break;
					case 'total_idle_3':
						$total_idle_3 		= $total_idle_3 - $break_time;
						$frequency_idle_3 	= $frequency_idle_3 - 1;
						break;
				}
			}
			else
			{
				switch ($idle_line) 
				{
					case 'total_idle_1':
						$total_idle_1 		= $total_idle_1 - $break_idle;
						$frequency_idle_1 	= $frequency_idle_1 - 1;
						break;
					case 'total_idle_2':
						$total_idle_2 		= $total_idle_2 - $break_idle;
						$frequency_idle_2 	= $frequency_idle_2 - 1;
						break;
					case 'total_idle_3':
						$total_idle_3 		= $total_idle_3 - $break_idle;
						$frequency_idle_3 	= $frequency_idle_3 - 1;
						break;
				}

				if($left_idle <= $idle_1)
				{
					$total_idle_1 		= $total_idle_1 + $left_idle;
					$frequency_idle_1 	= $frequency_idle_1 + 1;
				}
				elseif($left_idle < $idle_2)
				{
					$total_idle_2 		= $total_idle_2 + $left_idle;
					$frequency_idle_2 	= $frequency_idle_2 + 1;
				}
				elseif($left_idle >= $idle_2)
				{
					$total_idle_3 		= $total_idle_3 + $left_idle;
					$frequency_idle_3 	= $frequency_idle_3 + 1;
				}
			}



			//4. UPDATE ACTUAL AND MODIFIED STATUS
			if($is_absence==1)
			{
				$actual_status 			= 'NA';
				$modified_status 		= 'NA';
			}
			elseif($actual_status=='' && $schedule_start=='00:00:00' && $schedule_end=='00:00:00')
			{
				$actual_status 			= 'L';
			}
			elseif($actual_status=='' && gmdate('H:i:s', abs($margin_start))==$schedule_start && gmdate('H:i:s', (abs($margin_end)))==$schedule_end)
			{
				$margin_start 			= 0 - $margin_start;
				$actual_status 			= 'AS';
			}
			elseif($actual_status=='' && $margin_start>=0 && $margin_end>=0)
			{
				$actual_status 			= 'HB';
			}
			elseif($actual_status=='')
			{
				$actual_status 			= 'HC';
			}

			$total_active 				= abs($total_active) - abs($total_idle_1) - abs($total_idle_2) - abs($total_idle_3);

			$prev_data 					= $plog->ondate([(date('Y-m-d',strtotime($on. ' -1 day'))), (date('Y-m-d',strtotime($on. ' -1 day')))])->attendanceactualstatus($actual_status)->personid($model['attributes']['person_id'])->first();

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
							]
					);

			//5. SAVE
			if(isset($data->id))
			{
				$alog 										= AttendanceLog::processlogid($data->id)->orderBy('updated_at', 'desc')->first();
				$ilog 										= IdleLog::processlogid($data->id)->orderBy('updated_at', 'desc')->first();

				if(!$alog || (isset($modified_status) && $alog->modified_status != $modified_status) || (isset($actual_status) && $alog->actual_status != $actual_status))
				{
					$alog 									= new AttendanceLog;
				}

				if(!$ilog)
				{
					$ilog 									= new IdleLog;
				}
			}
			else
			{
				$alog 										= new AttendanceLog;
				$ilog 										= new IdleLog;
			}

			if(isset($prev_data->id))
			{
				$count_status 								= $prev_data->attendancelogs[0]->count_status + 1;
			}

			if(isset($modified_status) && isset($modified_by))
			{
				$alog->fill([
								'margin_start'				=> $margin_start,
								'margin_end'				=> $margin_end,
								'count_status'				=> $count_status,
								'actual_status'				=> $actual_status,
								'modified_status'			=> $modified_status,
								'modified_by'				=> $modified_by,
								'modified_at'				=> $modified_at,
				]);
			}
			elseif(isset($modified_status))
			{
				$alog->fill([
								'margin_start'				=> $margin_start,
								'margin_end'				=> $margin_end,
								'count_status'				=> $count_status,
								'actual_status'				=> $actual_status,
								'modified_status'			=> $modified_status,
				]);
			}
			else
			{
				$alog->fill([
								'margin_start'				=> $margin_start,
								'margin_end'				=> $margin_end,
								'count_status'				=> $count_status,
								'actual_status'				=> $actual_status,
				]);
			}

			$ilog->fill([
								'total_active'				=> $total_active,
								'total_idle'				=> $total_idle,
								'total_idle_1'				=> $total_idle_1,
								'total_idle_2'				=> $total_idle_2,
								'total_idle_3'				=> $total_idle_3,
								'break_idle'				=> $break_idle,
								'frequency_idle_1'			=> $frequency_idle_1,
								'frequency_idle_2'			=> $frequency_idle_2,
								'frequency_idle_3'			=> $frequency_idle_3,
			]);

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
			else
			{
				$alog->ProcessLog()->associate($plog);
				$ilog->ProcessLog()->associate($plog);

				if (!$alog->save())
				{
					$model['errors'] = $alog->getError();

					return false;
				}

				if (!$ilog->save())
				{
					$model['errors'] = $ilog->getError();

					return false;
				}
			}

			return true;
		}
		return true;
	}
}
