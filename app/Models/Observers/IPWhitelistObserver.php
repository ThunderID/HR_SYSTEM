<?php namespace App\Models\Observers;

use \Validator, Event;
use App\Events\CreateRecordOnTable;
use Illuminate\Support\MessageBag;
use App\Models\IPWhitelist;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class IPWhitelistObserver 
{
	public function saving($model)
	{
		$errors		= new MessageBag;

		if(is_null($model->id))
		{
			$id 	= 0;
		}
		else
		{
			$id 	= $model->id;
		}
	
		$ip_checker	= IPWhitelist::ip($model->ip)->notid($id)->first();

		if($ip_checker)
		{
			$errors->add('IP', 'IP sudah terdaftar');
		}

		if (!$errors->count())
		{
			return true;
		}
			
		$model['errors'] 		= $errors;

		return false;
	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Data IP';
		$attributes['notes'] 				= 'Menambah Data IP'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Data IP ';
		$attributes['notes'] 				= 'Mengubah Data IP '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Data IP';
		$attributes['notes'] 				= 'Menghapus Data IP'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
