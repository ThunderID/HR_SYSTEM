<?php namespace App\Models\Observers;

use \Validator, Event;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonObserver 
{
	public function saving($model)
	{
		if(isset($model['attributes']['last_password_updated_at']) && $model['attributes']['last_password_updated_at']=='0000-00-00 00:00:00')
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

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Data Personalia';
		$attributes['notes'] 				= 'Menambah Data Personalia'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Data Personalia ';
		$attributes['notes'] 				= 'Mengubah Data Personalia '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Data Personalia';
		$attributes['notes'] 				= 'Menghapus Data Personalia'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
