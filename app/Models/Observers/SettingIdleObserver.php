<?php namespace App\Models\Observers;

use \Validator;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class SettingIdleObserver 
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
		$errors 				= new MessageBag;

		$errors->add('idleupdate', 'Tidak dapat mengubah pengaturan idle. Silahkan Buat aturan yang baru.');
		
		return false;
	}

	public function deleting($model)
	{
		//
		$model['errors'] 		= ['Tidak dapat menghapus pengaturan idle. Silahkan Buat aturan yang baru.'];
		
		return false;
	}
}
