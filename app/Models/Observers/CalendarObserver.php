<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Calendar;

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
}
