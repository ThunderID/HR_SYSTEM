<?php namespace App\Models\Observers;

use \Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class MaritalStatusObserver 
{
	public function saving($model)
	{
		$validator 					= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 		= $validator->errors();

			return false;
		}
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Status Pernikahan';
		$attributes['notes'] 				= 'Menambah Status Pernikahan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Status Pernikahan ';
		$attributes['notes'] 				= 'Mengubah Status Pernikahan '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Status Pernikahan';
		$attributes['notes'] 				= 'Menghapus Status Pernikahan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
