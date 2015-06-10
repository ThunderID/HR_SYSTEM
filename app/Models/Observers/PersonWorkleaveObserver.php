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

class PersonWorkleaveObserver 
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
		$start 					= $model['attributes']['start'];
		$end 					= $model['attributes']['end'];
		if(isset($model->getDirty()['start']))
		{
			$start 				= $model->getOriginal()['start'];
		}

		if(isset($model->getDirty()['end']))
		{
			$end 				= $model->getOriginal()['end'];
		}

		$takenworkleave 		= Person::id($model['attributes']['person_id'])->takenworkleave(['on' => [$start, $end], 'status' => 'workleave'])->first();
	
		$workleavequota 		= Person::id($model['attributes']['person_id'])->Quotas(['ondate' => [$start, $end]])->first();

		if(isset($takenworkleave->takenworkleaves))
		{
			$taken 				= count($takenworkleave->takenworkleaves);
		}
		else
		{
			$taken 				= 0;
		}
		if(isset($workleavequota->quota) && ($workleavequota->quota + $workleavequota->plus_quota - $model->workleave->quota) < $taken)
		{
			$errors 			= new MessageBag;
			$errors->add('quota', 'Tidak dapat mengubah data cuti yang sudah terpakai.');
			
			$model['errors'] 	= $errors;
			
			return false;
		}
	}

	public function deleting($model)
	{
		if($model['attributes']['is_default'])
		{
			$model['errors']	= ['Tidak dapat menghapus hak cuti. Silahkan ubah hak cuti sebelum menghapus.'];
			
			return false;
		}

		$takenworkleave 		= Person::id($model['attributes']['person_id'])->takenworkleave(['on' => [$model['attributes']['start'], $model['attributes']['end']], 'status' => 'workleave'])->first();
	
		$workleavequota 		= Person::id($model['attributes']['person_id'])->Quotas(['ondate' => [$model['attributes']['start'], $model['attributes']['end']]])->first();
		
		if(($workleavequota->quota + $workleavequota->plus_quota - $model->workleave->quota) < count($takenworkleave->takenworkleaves))
		{
			$model['errors'] 	= ['Tidak dapat menghapus data cuti yang sudah terpakai.'];

			return false;
		}
	}
}
