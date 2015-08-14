<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Person;
use App\Models\Log;
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
				]);

				$person 					= Person::find($model['attributes']['person_id']);

				$data->Person()->associate($person);
				
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
