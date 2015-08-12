<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\PersonWorkleave;
use App\Models\Person;
use \Illuminate\Support\MessageBag as MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class ProcessLogObserver 
{
	public function saving($model)
	{
		$errors 								= new MessageBag;
		
		$validator 								= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 			= $validator->errors();

			return false;
		}
	}

	public function deleting($model)
	{
		if(date('Y-m-d',strtotime($model['attributes']['on'])) <= date('Y-m-d'))
		{
			$model['errors'] 			= ['Tidak dapat menghapus log yang sudah lewat dari tanggal hari ini.'];

			return false;
		}
	}

	public function deleted($model)
	{
		$pworkleaves 					= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->status(strtoupper($model['attributes']['modified_status']))->get();
		foreach ($pworkleaves as $key => $value) 
		{
			$dworkleave 				= PersonWorkleave::find($value->id);
			if(!$dworkleave->delete())
			{
				$model['errors']		= $dworkleave->getError();
			
				return false;
			}
		}

		return true;
	}

}
