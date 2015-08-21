<?php namespace App\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonObserver 
{
	public function saving($model)
	{
		if($model['attributes']['last_password_updated_at']=='0000-00-00 00:00:00')
		{
			$model->last_password_updated_at 	= $model->created_at->format('Y-m-d H:i:s');	
		}

		$validator 					= Validator::make($model['attributes'], $model['rules'], ['uniqid.required' => 'N I K tidak boleh kosong', 'uniqid.max' => 'Maksimal Panjang N I K : 255 Karakter']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['username']) && $model['attributes']['username']!='')
			{
				$validator 			= Validator::make($model['attributes'], ['uniqid' => 'unique:persons,uniqid,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : ''), 'username' => 'unique:persons,username,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : '')], ['uniqid.unique' => 'N I K sudah terpakai']);
			}
			else
			{
				$validator 			= Validator::make($model['attributes'], ['uniqid' => 'unique:persons,uniqid,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : '')], ['uniqid.unique' => 'N I K sudah terpakai']);
			}

			if ($validator->passes())
			{
				return true;
			}
			
			$model['errors'] 		= $validator->errors();

			return false;
		}
		else
		{
			$model['errors'] 		= $validator->errors();

			return false;
		}
	}

	public function deleting($model)
	{
		//
		if($model->works->count() || $model->contacts->count() || $model->relatives->count())
		{
			$model['errors'] 		= ['Tidak dapat menghapus data personalia yang memiliki pekerjaan atau informasi kontak atau relasi dengan karyawan'];

			return false;
		}

		return true;
	}
}
