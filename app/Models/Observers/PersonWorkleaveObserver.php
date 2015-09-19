<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Models\Person;
use App\Models\PersonSchedule;
use App\Models\Work;
use App\Models\PersonWorkleave;
use App\Models\Policy;
use \Illuminate\Support\MessageBag as MessageBag;
use DateTime, DateInterval, DatePeriod;
use App\Events\CreateRecordOnTable;
use Carbon\Carbon;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonWorkleaveObserver 
{
	public function saving($model)
	{
		$validator 						= Validator::make($model['attributes'], $model['rules'], ['work_id.required' => 'Cuti hanya dapat ditambahkan untuk pegawai aktif', 'work_id.exists' => 'Cuti hanya dapat ditambahkan untuk pegawai aktif']);

		if ($validator->passes())
		{
			if((isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0) && (!isset($model['attributes']['workleave_id'])))
			{
				$validator 				= Validator::make($model['attributes'], ['created_by' => 'required|exists:persons,id']);
			
				if (!$validator->passes())
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}
			}

			if(isset($model['attributes']['person_workleave_id']) && $model['attributes']['person_workleave_id']!=0)
			{
				$validator 				= Validator::make($model['attributes'], ['person_workleave_id' => 'exists:person_workleaves,id']);

				if (!$validator->passes())
				{
					$model['errors'] 		= $validator->errors();

					return false;
				}
			}
			elseif(isset($model['attributes']['workleave_id']) && $model['attributes']['workleave_id']!=0 && !isset($model->getDirty()['end']) && !isset($model->getDirty()['start']))
			{
				$work 					= Work::find($model['attributes']['work_id']);

				$prev_work 				= Work::active(false)->personid($model['attributes']['person_id'])->startbefore($work->start)->orderby('start', 'desc')->get();

				foreach ($prev_work as $key => $value) 
				{
					if($key > 0)
					{
						$prev_end 		= new Carbon($prev_work[$key-1]->end);
						$date_diff 		= $prev_end->diffInDays(new Carbon($value->start));

						//if end of prev work not same day of next start (idle), then start of work must be next start
						if($date_diff > 1)
						{
							$work_start = $value->start;
						}
						//else if end of prev work is same day of next start (idle), then start of work must be first work start
					}
					else
					{
						$work_start 	= $value->start;
					}
				}

				$start 					= max(date('Y-m-d', strtotime($model['attributes']['start'])), date('Y-m-d', strtotime($work_start)));

				$end 					= min(date('Y-m-d', strtotime($model['attributes']['end'])), (!is_null($work->end) ? date('Y-m-d', strtotime($work->end)) : date('Y-m-d')));

				$prev_wleave 			= PersonWorkleave::workid($model['attributes']['work_id'])->personid($model['attributes']['person_id'])->where('start', 'like', date('Y', strtotime($start)).'%')->quota(true)->sum('quota');

				if($prev_wleave)
				{
					$prev_quota			= $prev_wleave;
				}
				else
				{
					$prev_quota			= 0;
				}

				//if start = beginning of this year then end count one by one
				if($start == date('Y-m-d', strtotime($model['attributes']['start'])))
				{
					//hitung quota
					$quota 				= (((date('m', strtotime($end)) - date('m', strtotime($start)) + 2)/12)*12) - $prev_quota;

					//check policies
					$extendpolicy 		= Policy::organisationid($model->workleave->organisation_id)->type('extendsworkleave')->OnDate(date('Y-m-d H:i:s', strtotime($end)))->orderby('started_at', 'asc')->first();

					//check could be taken at
					$couldbetaken 		= date('Y-m-d', strtotime($start));

					if(is_null($extendpolicy))
					{
						$extendpolicy['value']	= '+ 3 months';
					}
				}
				//if start != beginning of this year then end count as one (consider first year's policies)
				else
				{
					//hitung quota
					if(date('d', strtotime($start)) != '1')
					{
						$quota 			= 0;
					}
					else
					{
						$quota 			= (((date('m', strtotime($end)) - date('m', strtotime($start)) + 2 )/12)*12) - $prev_quota;
					}

					//check policies
					// $extendpolicy 		= Policy::organisationid($model->workleave->organisation_id)->type('extendsmidworkleave')->OnDate(date('Y-m-d H:i:s', strtotime($end)))->orderby('started_at', 'asc')->first();
					
					// if(is_null($extendpolicy))
					// {
					// 	$extendpolicy['value']	= '+ 1 year + 3 months';
					// }

					$extendpolicy['value']	= '+ 3 months';
					
					//check could be taken at
					$couldbetaken 		= date('Y-m-d', strtotime($start. ' + 1 year'));
				}


				if((int)date('m', strtotime($start))==12 && $model->workleave->quota > 12)
				{
					$quota 				= $quota + ($model->workleave->quota-12);
				}

				$model->quota			= $quota;
				$model->start			= $couldbetaken;

				$model->end				= date('Y-m-d', strtotime('last day of December '.date('Y', strtotime($model['attributes']['end'])).' '.$extendpolicy['value']));
			}

			return true;
		}
		else
		{
			$model['errors'] 		= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		if ((isset($model['attributes']['person_id']) && (strtoupper($model['attributes']['status'])=='CB' || strtoupper($model['attributes']['status'])=='CN')) && $model['attributes']['quota'] < 0)
		{
			$errors 				= new MessageBag;
			
			$person 				= Person::find($model['attributes']['person_id']);

			$begin 					= new DateTime( $model['attributes']['start'] );
			$ended 					= new DateTime( $model['attributes']['end'].' + 1 day' );

			$interval 				= DateInterval::createFromDateString('1 day');
			$periods 				= new DatePeriod($begin, $interval, $ended);

			foreach ( $periods as $period )
			{
				$period1 			= new DateTime( $period->format('Y-m-d').' + 1 day' );
				
				//check if schedule were provided
				$schedule 			= new PersonSchedule;

				$psch 				= $schedule->personid($model['attributes']['person_id'])->ondate([$period->format('Y-m-d'), $period1->format('Y-m-d')])->status(strtoupper($model['attributes']['status']))->first();
				if(!$psch)
				{
					$schedule->fill([
						'created_by'	=>  $model['attributes']['created_by'],
						'name'			=>  $model['attributes']['name'],
						'status'		=>  strtoupper($model['attributes']['status']),
						'on'			=>  $period->format('Y-m-d'),
						'start'			=>  '00:00:00',
						'end'			=>  '00:00:00',
					]);
					
					$schedule->Person()->associate($person);
					
					if (!$schedule->save())
					{
						$model['errors'] = $schedule->getError();
						return false;
					}
				}
				else
				{
					$psch->fill([
						'created_by'	=>  $model['attributes']['created_by'],
						'name'			=>  $model['attributes']['name'],
						'status'		=>  strtoupper($model['attributes']['status']),
						'on'			=>  $period->format('Y-m-d'),
						'start'			=>  '00:00:00',
						'end'			=>  '00:00:00',
					]);
					
					$psch->Person()->associate($person);
					
					if (!$psch->save())
					{
						$model['errors'] = $psch->getError();
						return false;
					}
				}
			}
		}
	}

	public function updating($model)
	{
		if(isset($model->getDirty()['person_workleave_id']) && !$model->getOriginal()['person_workleave_id']==0)
		{
			$errors 				= new MessageBag;
			$errors->add('quota', 'Tidak dapat mengubah data cuti.');
			
			$model['errors'] 		= $errors;

			return false;
		}
	}

	public function deleting($model)
	{
		if($model->childs->count())
		{
			$model['errors']		= ['Tidak dapat menghapus cuti yang telah dipakai.'];
				
			return false;
		}
	}

	public function created($model)
	{
		if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0)
		{
			$attributes['person_id'] 			= $model->created_by;
			$attributes['record_log_id'] 		= $model->id;
			$attributes['record_log_type'] 		= get_class($model);
			$attributes['name'] 				= 'Mengubah Cuti';
			$attributes['notes'] 				= 'Mengubah Cuti'.' pada '.date('d-m-Y');
			$attributes['action'] 				= 'delete';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function updated($model)
	{
		if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0)
		{
			$attributes['person_id'] 			= $model->created_by;
			$attributes['record_log_id'] 		= $model->id;
			$attributes['record_log_type'] 		= get_class($model);
			$attributes['name'] 				= 'Mengubah Cuti';
			$attributes['notes'] 				= 'Mengubah Cuti'.' pada '.date('d-m-Y');
			$attributes['old_attribute'] 		= json_encode($model->getOriginal());
			$attributes['new_attribute'] 		= json_encode($model->getAttributes());
			$attributes['action'] 				= 'save';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Cuti';
		$attributes['notes'] 				= 'Menghapus Cuti'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));

		$pschedules 						= PersonSchedule::personid($model['attributes']['person_id'])->ondate([$model['attributes']['start'], $model['attributes']['end']])->status(strtoupper($model['attributes']['status']))->get();
		foreach ($pschedules as $key => $value) 
		{
			$dschedule 						= PersonSchedule::find($value->id);
			if(!$dschedule->delete())
			{
				$model['errors']			= $dschedule->getError();
			
				return false;
			}
		}

		return true;
	}
}
