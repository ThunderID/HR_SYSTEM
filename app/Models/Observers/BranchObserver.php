<?php namespace App\Models\Observers;

use Validator, Event;
use App\Models\Person;
use App\Models\Chart;
use App\Models\Work;
use App\Models\Contact;
use App\Models\FingerPrint;
use App\Events\CreateRecordOnTable;

/* ----------------------------------------------------------------------
 * Event:
 * 	Created						
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class BranchObserver 
{
	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Cabang dari unit bisnis '.$model->organisation->name;
		$attributes['notes'] 				= 'Menambah Cabang dari unit bisnis '.$model->organisation->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));

		$data 					= new FingerPrint;
		$data->branch()->associate($model);
		if(!$data->save())
		{
			$model['errors']	= $data->getError();
		}
		return true;
	}

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
		if($model->charts->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus cabang yang memiliki departemen.'];

			return false;
		}

		$contact 				= new Contact;
		$deleted 				= $contact->branchid($model['attributes']['id'])->delete();

		if(!$deleted)
		{
			$model['errors']	= $contact->getError();
		}

		$finger 				= new FingerPrint;
		$deleted 				= $finger->branchid($model['attributes']['id'])->first();

		if(!$deleted->delete())
		{
			$model['errors']	= $deleted->getError();
		}

		return true;
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Cabang dari unit bisnis '.$model->organisation->name;
		$attributes['notes'] 				= 'Mengubah Cabang dari unit bisnis '.$model->organisation->name.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Cabang dari unit bisnis '.$model->organisation->name;
		$attributes['notes'] 				= 'Menghapus Cabang dari unit bisnis '.$model->organisation->name.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
