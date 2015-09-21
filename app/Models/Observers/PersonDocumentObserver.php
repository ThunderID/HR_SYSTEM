<?php namespace App\Models\Observers;

use \Validator, Event;
use \App\Models\DocumentDetail;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonDocumentObserver 
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
		if($model->document->is_required)
		{
			$model['errors'] 	= ['Tidak dapat menghapus dokumen yang wajib ada'];

			return false;
		}
		else
		{
			foreach ($model->details as $key => $value) 
			{
				$detail 	 			= new DocumentDetail;
				$delete 				= $detail->find($value['id']);

				if($delete && !$delete->delete())
				{
					$model['errors']	= $delete->getError();
					
					return false;
				}
			}
		}

		return true;
	}

	public function created($model)
	{
		if(isset($model['attributes']['person_id']))
		{
			$attributes['record_log_id'] 	= $model->id;
			$attributes['record_log_type'] 	= get_class($model);
			$attributes['name'] 			= 'Menambah Dokumen milik '.$model->person->name;
			$attributes['notes'] 			= 'Menambah Dokumen milik '.$model->person->name.' pada '.date('d-m-Y');
			$attributes['action'] 			= 'delete';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function updated($model)
	{
		if(isset($model['attributes']['person_id']))
		{
			$attributes['record_log_id'] 	= $model->id;
			$attributes['record_log_type'] 	= get_class($model);
			$attributes['name'] 			= 'Mengubah Dokumen milik '.$model->person->name;
			$attributes['notes'] 			= 'Mengubah Dokumen milik '.$model->person->name.' pada '.date('d-m-Y');
			$attributes['old_attribute'] 	= json_encode($model->getOriginal());
			$attributes['new_attribute'] 	= json_encode($model->getAttributes());
			$attributes['action'] 			= 'save';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}

	public function deleted($model)
	{
		if(isset($model['attributes']['person_id']))
		{
			$attributes['record_log_id'] 	= $model->id;
			$attributes['record_log_type'] 	= get_class($model);
			$attributes['name'] 			= 'Menghapus Dokumen milik '.$model->person->name;
			$attributes['notes'] 			= 'Menghapus Dokumen milik '.$model->person->name.' pada '.date('d-m-Y');
			$attributes['action'] 			= 'restore';

			Event::fire(new CreateRecordOnTable($attributes));
		}
	}
}
