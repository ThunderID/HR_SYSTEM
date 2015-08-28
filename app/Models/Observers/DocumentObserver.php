<?php namespace App\Models\Observers;

use \Validator, Event;
use \App\Models\Template;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class DocumentObserver 
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
		if($model->persons->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus dokumen yang berkaitan dengan karyawan atau yang memiliki template'];

			return false;
		}
		else
		{
			foreach ($model->templates as $key => $value) 
			{
				$template 	 			= new Template;
				$delete 				= $template->find($value['id']);

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
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Dokumen';
		$attributes['notes'] 				= 'Menambah Dokumen'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Dokumen';
		$attributes['notes'] 				= 'Mengubah Dokumen'.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Dokumen';
		$attributes['notes'] 				= 'Menghapus Dokumen'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
