<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Person;
use App\Models\Work;
use \Illuminate\Support\MessageBag as MessageBag;

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
		if(isset($model->getDirty()['quota']) && $model->persons->count())
		{
			$errors 			= new MessageBag;
			$errors->add('quota', 'Perubahan hak cuti untuk karyawan tidak dapat dilakukan. Silahkan tambahkan template cuti yang baru.');
			
			$model['errors'] 	= $errors;

			return false;
		}
	}

	public function deleting($model)
	{
		if($model->persons()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus data cuti.'];

			return false;
		}
	}
}
