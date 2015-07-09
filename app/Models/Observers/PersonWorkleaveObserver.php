<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Person;
use App\Models\PersonSchedule;
use App\Models\Work;
use \Illuminate\Support\MessageBag as MessageBag;
use DateTime, DateInterval, DatePeriod;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonWorkleaveObserver 
{
	public function saving($model)
	{
		$validator 					= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['person_workleave_id']) && $model['attributes']['person_workleave_id']!=0 && strtolower($model['attributes']['status'])=='confirmed' && (int)$model->parent->quota <= (int)$model['attributes']['quota'])
			{
				$validator 				= Validator::make($model['attributes'], ['person_workleave_id' => 'exists:person_workleaves,person_workleave_id']);

				if (!$validator->passes())
				{
					$model['errors'] 		= $validator->errors();

					return false;
				}

				$errors 			= new MessageBag;
				$errors->add('quota', 'Quota cuti yang tersedia tidak mencukupi. Sisa cuti = '.(int)$model->parent->quota.'. Jika cuti merupakan kasus khusus silahkan tambahkan cuti istimewa.');
				
				$model['errors'] 	= $errors;

				return false;
			}

			return true;
		}
		else
		{
			$model['errors'] 		= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		if (isset($model['attributes']['person_id']) && (strtoupper($model['attributes']['status'])=='CB' || strtoupper($model['attributes']['status'])=='CI'  || strtoupper($model['attributes']['status'])=='CONFIRMED'))
		{
			$errors 				= new MessageBag;
			
			$person 				= Person::find($model['attributes']['person_id']);

			$begin 					= new DateTime( $model['attributes']['start'] );
			$ended 					= new DateTime( $model['attributes']['end'].' + 1 day' );

			$interval 				= DateInterval::createFromDateString('1 day');
			$periods 				= new DatePeriod($begin, $interval, $ended);

			foreach ( $periods as $period )
			{
				$schedule 			= new PersonSchedule;
				$psch 				= $schedule->ondate([$period->format('Y-m-d'), $period->format('Y-m-d')])->status(strtoupper($model['attributes']['status'])=='CONFIRMED' ? 'CN' : strtoupper($model['attributes']['status']))->first();
				if($psch)
				{
					$schedule 		= $psch;
				}

				$schedule->fill([
					'created_by'	=>  $model['attributes']['created_by'],
					'name'			=>  $model['attributes']['name'],
					'status'		=>  (strtoupper($model['attributes']['status'])=='CONFIRMED' ? 'CN' : strtoupper($model['attributes']['status'])),
					'on'			=>  $period->format('Y-m-d'),
					'start'			=>  '00:00:00',
					'end'			=>  '00:00:00',
				]);
				
				$schedule->Person()->associate($person);
				
				if (!$schedule->save())
				{
					$model['errors'] = $schedule->getError();
					return false;
				}
			}
		}
	}

	public function updating($model)
	{
		if(isset($model->getDirty()['person_workleave_id']) && !$model->getOriginal()['person_workleave_id']==0)
		{
			$errors 			= new MessageBag;
			$errors->add('quota', 'Tidak dapat mengubah data cuti.');
			
			$model['errors'] 	= $errors;

			return false;
		}

		// $start 					= $model['attributes']['start'];
		// $end 					= $model['attributes']['end'];
		// if(isset($model->getDirty()['start']))
		// {
		// 	$start 				= $model->getOriginal()['start'];
		// }

		// if(isset($model->getDirty()['end']))
		// {
		// 	$end 				= $model->getOriginal()['end'];
		// }

		// $takenworkleave 		= Person::id($model['attributes']['person_id'])->takenworkleave(['on' => [$start, $end], 'status' => 'workleave'])->first();
	
		// $workleavequota 		= Person::id($model['attributes']['person_id'])->Quotas(['ondate' => [$start, $end]])->first();

		// if(isset($takenworkleave->takenworkleaves))
		// {
		// 	$taken 				= count($takenworkleave->takenworkleaves);
		// }
		// else
		// {
		// 	$taken 				= 0;
		// }
		// if(isset($workleavequota->quota) && ($workleavequota->quota + $workleavequota->plus_quota - $model->workleave->quota) < $taken)
		// {
		// 	$errors 			= new MessageBag;
		// 	$errors->add('quota', 'Tidak dapat mengubah data cuti yang sudah terpakai.');
			
		// 	$model['errors'] 	= $errors;
			
		// 	return false;
		// }
	}

	public function deleting($model)
	{
		if(isset($model['attributes']['person_workleave_id']) && $model['attributes']['person_workleave_id']!=0)
		{
			$model['errors']	= ['Tidak dapat menghapus hak cuti. Silahkan ubah hak cuti sebelum menghapus.'];
				
			return false;
		}

		// $takenworkleave 		= Person::id($model['attributes']['person_id'])->takenworkleave(['on' => [$model['attributes']['start'], $model['attributes']['end']], 'status' => 'workleave'])->first();
	
		// $workleavequota 		= Person::id($model['attributes']['person_id'])->Quotas(['ondate' => [$model['attributes']['start'], $model['attributes']['end']]])->first();
		
		// if(($workleavequota->quota + $workleavequota->plus_quota - $model->workleave->quota) < count($takenworkleave->takenworkleaves))
		// {
		// 	$model['errors'] 	= ['Tidak dapat menghapus data cuti yang sudah terpakai.'];

		// 	return false;
		// }
	}
}
