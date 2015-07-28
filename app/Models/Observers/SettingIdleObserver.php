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
			$validator 	 		= Validator::make(['margin_bottom_idle' => ($model['attributes']['margin_bottom_idle']/60), 'idle_1' => ($model['attributes']['idle_1']/60), 'idle_2' => ($model['attributes']['idle_2']/60)], ['margin_bottom_idle' => 'numeric|min:0|max:1440', 'idle_1' => 'numeric|min:'.($model['attributes']['margin_bottom_idle']/60).'|max:'.($model['attributes']['idle_2']/60), 'idle_2' => 'numeric|min:'.($model['attributes']['idle_1']/60).'|max:1440']);
			if ($validator->passes())
			{
				return true;
			}
			else
			{
				dd($validator);
				$model['errors'] = $validator->errors();

				return false;
			}
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
		if(isset($model->getDirty()['start']) || isset($model->getDirty()['idle_1']) || isset($model->getDirty()['idle_2']))
		{
			$errors 				= new MessageBag;

			$errors->add('idleupdate', 'Tidak dapat mengubah pengaturan idle. Silahkan Buat aturan yang baru.');
		
			$model['errors'] 		= $errors;
			
			return false;
		}
	}

	public function deleting($model)
	{
		//
		$model['errors'] 		= ['Tidak dapat menghapus pengaturan idle. Silahkan Buat aturan yang baru.'];
		
		return false;
	}
}
