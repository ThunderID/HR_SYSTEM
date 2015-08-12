<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Schedule;
use App\Models\ProcessLog;
use App\Models\Work;
use App\Models\Person;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class ScheduleObserver 
{
	public function saving($model)
	{
		$validator 							= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['calendar_id']))
			{
				$schedule 					= new Schedule;
				if(isset($model['attributes']['id']))
				{
					$data 					= $schedule->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->calendarid($model['attributes']['calendar_id'])->notid($model['attributes']['id'])->first();
				}
				else
				{
					$data 					= $schedule->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->calendarid($model['attributes']['calendar_id'])->first();
				}

				if(count($data))
				{
					$errors 				= new MessageBag;
					$errors->add('ondate', 'Tidak dapat menyimpan dua jadwal di hari yang sama. Silahkan edit jadwal sebelumnya tambahkan jadwal khusus pada orang yang bersangkutan.');
					
					$model['errors'] 		= $errors;

					return false;
				}
			}

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
		//scheme change status to HB
		$on 							 	= date('Y-m-d', strtotime($model['attributes']['on']));
		$on1 							 	= date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'));
		if(isset($model['attributes']['calendar_id']) && $model['attributes']['calendar_id'] != 0)
		{
			//check process log where not generated from persons schedule
			$logs 							= Log::ondate([$on, $on1])->hasnoschedule(['on' => $on])->WorkCalendar(['id' => $model['attributes']['calendar_id'], 'start' => $on])->get();
			
			if($logs->count())
			{
				foreach ($logs as $key => $value) 
				{
					//update resave 
					$data					= Log::ID($value->id)->first();

					if(!$data->save())
					{
						$model['errors']	= $data->getError();
						return false;
					}
				}
				return true;
			}
		}
	}

	public function deleting($model)
	{
		if(date('Y-m-d', strtotime($model['attributes']['on'])) < date('Y-m-d'))
		{
			$model['errors'] 				= ['Tidak dapat menghapus jadwal.'];

			return false;
		}

		return true;
	}
}
