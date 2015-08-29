<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Models\Schedule;
use App\Models\Log;
use \Illuminate\Support\MessageBag as MessageBag;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class ScheduleObserver 
{
	public function creating($model)
	{
		if(isset($model['attributes']['calendar_id']) )
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
	}
	
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
					$data->fill(['created_by' => $model['attributes']['created_by']]);

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

	public function created($model)
	{
		if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0)
		{
			$attributes['person_id'] 			= $model->created_by;
			$attributes['record_log_id'] 		= $model->id;
			$attributes['record_log_type'] 		= get_class($model);
			$attributes['name'] 				= 'Mengubah Jadwal '.$model->calendar->name;
			$attributes['notes'] 				= 'Mengubah Jadwal '.$model->calendar->name.' pada '.date('d-m-Y');
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
			$attributes['name'] 				= 'Mengubah Jadwal '.$model->calendar->name;
			$attributes['notes'] 				= 'Mengubah Jadwal '.$model->calendar->name.' pada '.date('d-m-Y');
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
		$attributes['name'] 				= 'Menghapus Jadwal '.$model->calendar->name;
		$attributes['notes'] 				= 'Menghapus Jadwal '.$model->calendar->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));

		//deleting schedule recalculate process log 
		$on 								= date('Y-m-d', strtotime($model['attributes']['on']));
		$on1 								= date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'));

		$logs 								= Log::ondate([$on, $on1])->hasnoschedule(['on' => $on])->WorkCalendar(['id' => $model['attributes']['calendar_id'], 'start' => $on])->get();
		
		if($logs->count())
		{
			foreach ($logs as $key => $value) 
			{
				$slog 						= Log::find($value->id);
				if(!$slog->save())
				{
					$model['errors']		= $slog->getError();
				
					return false;
				}
			}
		}

		return true;
	}
}
