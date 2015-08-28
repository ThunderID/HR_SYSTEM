<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Models\Person;
use App\Models\Work;
use \Illuminate\Support\MessageBag as MessageBag;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class WorkleaveObserver 
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

	public function updating($model)
	{
		if(isset($model->getDirty()['quota']))
		{
			$errors 			= new MessageBag;
			$errors->add('quota', 'Perubahan hak cuti untuk karyawan tidak dapat dilakukan. Silahkan tambahkan template cuti yang baru.');
			
			$model['errors'] 	= $errors;

			return false;
		}
	}

	public function deleting($model)
	{
		if($model->personworkleaves->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus data cuti yang menjadi acuan cuti karyawan. Silahkan non aktif kan data cuti yang tidak berlaku lagi.'];

			return false;
		}
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Template Cuti';
		$attributes['notes'] 				= 'Menambah Template Cuti'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Template Cuti ';
		$attributes['notes'] 				= 'Mengubah Template Cuti '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Template Cuti';
		$attributes['notes'] 				= 'Menghapus Template Cuti'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
