<?php namespace App\Models\Observers;

use \Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class ApiObserver
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['client']) && isset($model['attributes']['id']))
			{
				$validator 		= Validator::make(['client' => $model['attributes']['client']], ['client' => 'unique:apis,client,'.$model['attributes']['id']]);

				if(!$validator->passes())
				{
					$model['errors']	= $validator->errors();

					return false;
				}
			}
			else
			{
				return true;
			}
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Pengaturan API Key';
		$attributes['notes'] 				= 'Menambah Pengaturan API Key'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Pengaturan API Key ';
		$attributes['notes'] 				= 'Mengubah Pengaturan API Key '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Pengaturan API Key';
		$attributes['notes'] 				= 'Menghapus Pengaturan API Key'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
