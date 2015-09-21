<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class ChartWorkleaveObserver 
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

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Template Cuti Tahunan';
		$attributes['notes'] 				= 'Menambah Template Cuti Tahunan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Template Cuti Tahunan ';
		$attributes['notes'] 				= 'Mengubah Template Cuti Tahunan '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Template Cuti Tahunan';
		$attributes['notes'] 				= 'Menghapus Template Cuti Tahunan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
