<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Person;
use App\Models\PersonSchedule;
use App\Models\Work;
use App\Models\PersonWorkleave;
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
		$validator 						= Validator::make($model['attributes'], $model['rules'], ['work_id.required' => 'Cuti hanya dapat ditambahkan untuk pegawai aktif', 'work_id.exists' => 'Cuti hanya dapat ditambahkan untuk pegawai aktif']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['person_workleave_id']) && $model['attributes']['person_workleave_id']!=0)
			{
				$left_quota 			= PersonWorkleave::id($model['attributes']['person_workleave_id'])->first();
				$add_quota 				= PersonWorkleave::parentid($model['attributes']['person_workleave_id'])->sum('quota');

				$validator 				= Validator::make($model['attributes'], ['person_workleave_id' => 'exists:person_workleaves,id']);

				if (!$validator->passes())
				{
					$model['errors'] 		= $validator->errors();

					return false;
				}
				// elseif(in_array(strtoupper($model['attributes']['status']), ['CB', 'CN']) && ($left_quota->quota + $add_quota + $model['attributes']['quota']) < 0)
				// {
				// 	$errors 			= new MessageBag;
				// 	$errors->add('quota', 'Quota '.$left_quota->name.' yang tersedia tidak mencukupi. <br/>Sisa cuti = '.(int)($left_quota->quota + $add_quota).' hari, Cuti yang hendak diambil = '.abs($model['attributes']['quota']).' hari.<br/>Jika cuti merupakan kasus khusus silahkan tambahkan cuti istimewa.');
					
				// 	$model['errors'] 	= $errors;
					
				// 	return false;
				// }
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
		if ((isset($model['attributes']['person_id']) && (strtoupper($model['attributes']['status'])=='CB' || strtoupper($model['attributes']['status'])=='CN')) && $model['attributes']['quota'] < 0)
		{
			$errors 				= new MessageBag;
			
			$person 				= Person::find($model['attributes']['person_id']);

			$begin 					= new DateTime( $model['attributes']['start'] );
			$ended 					= new DateTime( $model['attributes']['end'].' + 1 day' );

			$interval 				= DateInterval::createFromDateString('1 day');
			$periods 				= new DatePeriod($begin, $interval, $ended);

			foreach ( $periods as $period )
			{
				$period1 			= new DateTime( $period->format('Y-m-d').' + 1 day' );
				
				//check if schedule were provided
				$schedule 			= new PersonSchedule;

				$psch 				= $schedule->personid($model['attributes']['person_id'])->ondate([$period->format('Y-m-d'), $period1->format('Y-m-d')])->status(strtoupper($model['attributes']['status']))->first();
				if(!$psch)
				{
					$schedule->fill([
						'created_by'	=>  $model['attributes']['created_by'],
						'name'			=>  $model['attributes']['name'],
						'status'		=>  strtoupper($model['attributes']['status']),
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
				else
				{
					$psch->fill([
						'created_by'	=>  $model['attributes']['created_by'],
						'name'			=>  $model['attributes']['name'],
						'status'		=>  strtoupper($model['attributes']['status']),
						'on'			=>  $period->format('Y-m-d'),
						'start'			=>  '00:00:00',
						'end'			=>  '00:00:00',
					]);
					
					$psch->Person()->associate($person);
					
					if (!$psch->save())
					{
						$model['errors'] = $psch->getError();
						return false;
					}
				}
			}
		}
	}

	public function updating($model)
	{
		if(isset($model->getDirty()['person_workleave_id']) && !$model->getOriginal()['person_workleave_id']==0)
		{
			$errors 				= new MessageBag;
			$errors->add('quota', 'Tidak dapat mengubah data cuti.');
			
			$model['errors'] 		= $errors;

			return false;
		}
	}

	public function deleting($model)
	{
		if($model->childs->count())
		{
			$model['errors']		= ['Tidak dapat menghapus cuti yang telah dipakai.'];
				
			return false;
		}
	}

	public function deleted($model)
	{
		$pschedules 				= PersonSchedule::personid($model['attributes']['person_id'])->ondate([$model['attributes']['start'], $model['attributes']['end']])->status(strtoupper($model['attributes']['status']))->get();
		foreach ($pschedules as $key => $value) 
		{
			$dschedule 				= PersonSchedule::find($value->id);
			if(!$dschedule->delete())
			{
				$model['errors']	= $dschedule->getError();
			
				return false;
			}
		}

		return true;
	}
}
