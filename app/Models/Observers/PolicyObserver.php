<?php namespace App\Models\Observers;

use \Validator;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PolicyObserver 
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
		//
		if(isset($model->getDirty()['started_at']))
		{
			$errors 				= new MessageBag;

			$errors->add('idleupdate', 'Tidak dapat mengubah pengaturan kebijakan. Silahkan Buat kebijakan yang baru.');
		
			$model['errors'] 		= $errors;
			
			return false;
		}
	}

	public function deleting($model)
	{
		//
		// $model['errors'] 		= ['Tidak dapat menghapus pengaturan kebijakan. Silahkan Buat kebijakan yang baru.'];
		
		// return false;
	}
}
