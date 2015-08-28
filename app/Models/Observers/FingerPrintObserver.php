<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class FingerPrintObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['branch_id']) && isset($model['attributes']['id']))
			{
				$validator 		= Validator::make(['branch_id' => $model['attributes']['branch_id']], ['branch_id' => 'unique:finger_prints,branch_id,'.$model['attributes']['id']]);

				if(!$validator->passes())
				{
					$model['errors']	= $validator->errors();

					return false;
				}
			}
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
		$attributes['name'] 				= 'Menambah Data Sidik Jari Hari Ini';
		$attributes['notes'] 				= 'Menambah Data Sidik Jari Hari Ini'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Data Sidik Jari Hari Ini ';
		$attributes['notes'] 				= 'Mengubah Data Sidik Jari Hari Ini '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Data Sidik Jari Hari Ini';
		$attributes['notes'] 				= 'Menghapus Data Sidik Jari Hari Ini'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
