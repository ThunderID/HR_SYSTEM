<?php namespace App\Models\Observers;

use Validator;
use Hash;
use App\Models\Person;
use App\Models\Chart;
use App\Models\Work;
use App\Models\Contact;
use App\Models\FingerPrint;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class BranchObserver 
{
	public function created($model)
	{
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
}
