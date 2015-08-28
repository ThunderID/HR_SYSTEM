<?php namespace App\Models\Observers;

use \Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class ApplicationObserver
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function deleting($model)
	{
		//
		if($model->menus()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus aplikasi yang memiliki menu pilihan'];

			return false;
		}

		return true;
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Aplikasi';
		$attributes['notes'] 				= 'Menambah Aplikasi'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Aplikasi ';
		$attributes['notes'] 				= 'Mengubah Aplikasi '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Aplikasi';
		$attributes['notes'] 				= 'Menghapus Aplikasi'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
