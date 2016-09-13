<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Models\Person;
use App\Models\Log;
use App\Events\CreateRecordOnTable;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Deleted						
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

	public function created($model)
	{
		if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0 && $model->person()->count())
		{
			$attributes['person_id'] 			= $model->created_by;
			$attributes['record_log_id'] 		= $model->id;
			$attributes['record_log_type'] 		= get_class($model);
			$attributes['name'] 				= 'Mengubah Jadwal '.$model->person->name;
			$attributes['notes'] 				= 'Mengubah Jadwal '.$model->person->name.' pada '.date('d-m-Y');
			$attributes['action'] 				= 'delete';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function updated($model)
	{
		if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0 && $model->person()->count())
		{
			$attributes['person_id'] 			= $model->created_by;
			$attributes['record_log_id'] 		= $model->id;
			$attributes['record_log_type'] 		= get_class($model);
			$attributes['name'] 				= 'Mengubah Jadwal '.$model->person->name;
			$attributes['notes'] 				= 'Mengubah Jadwal '.$model->person->name.' pada '.date('d-m-Y');
			$attributes['old_attribute'] 		= json_encode($model->getOriginal());
			$attributes['new_attribute'] 		= json_encode($model->getAttributes());
			$attributes['action'] 				= 'save';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function saved($model)
	{
		$errors 							= new MessageBag;

		$on 							 	= date('Y-m-d', strtotime($model['attributes']['on']));
		$on1 							 	= date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'));

		if(isset($model['attributes']['person_id']) && $model['attributes']['person_id'] != 0)
		{
			//check previous log if there was, then just modify the start
			$logs 							= Log::ondate([$on, $on1])->personid($model['attributes']['person_id'])->get();
			if($logs->count())
			{
				foreach ($logs as $key => $value) 
				{
					$data					= Log::ID($value->id)->first();
					$data->fill(['created_by' => $model['attributes']['created_by']]);

					if(!$data->save())
					{
						$model['errors']	= $data->getError();

						return false;
					}
				}
			}
			else
			{
				$data 						= new Log;
				$data->fill([
						'created_by'		=> $model['attributes']['created_by'],
						'name'				=> $model['attributes']['name'],
						'on'				=> date('Y-m-d H:i:s',strtotime($on)),
						'last_input_time'	=> date('Y-m-d H:i:s',strtotime($on)),
						'pc'				=> 'HRIS',
						'app_version'		=> 'WEB',
						'ip'				=> getenv("REMOTE_ADDR"),
						'person_id'			=> $model['attributes']['person_id'],
				]);

				// $person 					= Person::find($model['attributes']['person_id']);

				// $data->Person()->associate($person);
				
				if(!$data->save())
				{
					$model['errors']		= $data->getError();

					return false;
				}
			}
		}
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Jadwal '.$model->person->name;
		$attributes['notes'] 				= 'Menghapus Jadwal '.$model->person->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));

		//deleting person schedule recalculate process log 
		$logs 								= Log::personid($model['attributes']['person_id'])->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->get();

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
