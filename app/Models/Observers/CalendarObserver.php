<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Models\Calendar;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class CalendarObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['import_from_id']) && ($model['attributes']['import_from_id']!='0' && $model['attributes']['import_from_id']!=''))
			{
				$validator 		= Validator::make($model['attributes'], ['import_from_id' => 'exists:tmp_calendars,id']);
				
				if ($validator->passes())
				{
					return true;
				}
				
				$model['errors'] 		= $validator->errors();

				return false;
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

	public function saved($model)
	{
		if(isset($model['attributes']['organisation_id']) && $model->getDirty('organisation_id') != $model['attributes']['organisation_id'])
		{
			$model['errors'] 	= ['Organisasi tidak valid'];

			return false;
		}
		return true;
	}

	public function deleting($model)
	{
		if($model->childs->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus kalender yang diikuti oleh kalender lain'];

			return false;
		}

		if($model->charts->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus kalender yang berkaitan dengan karyawan'];

			return false;
		}

		if($model->schedules->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus kalender yang memiliki jadwal'];

			return false;
		}
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Kalender '.$model->name;
		$attributes['notes'] 				= 'Mengubah Kalender '.$model->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Kalender '.$model->name;
		$attributes['notes'] 				= 'Mengubah Kalender '.$model->name.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= $model->getOriginal();
		$attributes['new_attribute'] 		= $model->getAttributes();
		$attributes['action'] 				= 'save';
		
		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Kalender '.$model->name;
		$attributes['notes'] 				= 'Menghapus Kalender '.$model->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
