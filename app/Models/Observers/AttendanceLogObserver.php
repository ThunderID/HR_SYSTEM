<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\SettingIdle;
use Illuminate\Support\Person;
use Illuminate\Support\PersonWorkleave;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class AttendanceLogObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			//save actual status for both start and end
			if(!isset($model['attributes']['actual_status']))
			{
				$actual_status 					= '';

				if($model->margin_start==0 && $model->margin_end==0)
				{
					$actual_status 				= 'AS';
				}
				elseif($model->margin_start>=0 && $model->margin_end>=0)
				{
					$actual_status 				= 'HB';
				}
				else
				{
					$actual_status 				= 'HC';
				}

				$model->fill(['actual_status' => $actual_status]);
			}
			//save modfied status
			if(isset($model->getDirty()['modified_status']))
			{
				if(strtoupper($model->actual_status)=='HB')
				{
					$errors->add('modified', 'Tidak dapat mengubah status dengan status awal HB');
					$model['errors'] 			= $errors;
					
					return false;
				}
				elseif(strtoupper($model->actual_status)=='HC' && !in_array(strtoupper($model->modified_status), ['HT', 'HD', 'HC', 'HP']))
				{
					$errors->add('modified', 'Status untuk kedatangan cacat tidak valid. Pilih diantara status berikut : HT, HD, HC, HP');
					$model['errors'] 			= $errors;
					
					return false;
				}
				elseif(strtoupper($model->actual_status)=='AS' && !in_array(strtoupper($model->modified_status), ['DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'AS']))
				{
					$errors->add('modified', 'Status untuk absensi tidak valid. Pilih diantara status berikut : DN, SS, SL, CN, CB, CI, UL, AS');
					
					$model['errors'] 			= $errors;
					
					return false;
				}

				$model->fill(['modified_at' => date('Y-m-d H:i:s', strtotime('now'))]);
			}

			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		if(!is_null($model->settlement_at))
		{
			$errors 					= new MessageBag;
			$errors->add('settle', 'Tidak dapat mengubah status yang sudah settle');

			$model['errors'] 			= $validator->errors();
			
			return false;
		}
		elseif(strtoupper($model->actual_status)=='AS' && (in_array(strtoupper($model->modified_status), ['CN','CI','CB'])))
		{
			//check if pw on that day were provided
			$pwM 						= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([date('Y-m-d', strtotime($model['attributes']['on'])), null])->status(strtoupper($model->modified_status))->quota(false)->first();
			if($pwM)
			{
				return true;
			}
			//check if pw on that day were not provided
			else
			{
				$pwP 					= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([date('Y-m-d', strtotime($model['attributes']['on'])), null])->status('CN')->quota(true)->first();
				if(!$pwP)
				{
					$errors->add('Workleave', 'Quota Cuti Tidak Tersedia. Jika ingin menambahkan hutang cuti, silahkan tambahkan quota cuti tahun berikutnya.');
					$model['errors'] 	= $errors;

					return false;
				}

				$person 				= Person::find($model['attributes']['person_id']);

				$pworkleave 			= new PersonWorkleave;
				$pworkleave->fill([
						'work_id'				=> $model['attributes']['work_id'],
						'person_workleave_id'	=> $pwP->id,
						'created_by'			=> $model['attributes']['modified_by'],
						'name'					=> 'Pengambilan '.$pwP->name,
						'status'				=> $model['attributes']['modified_status'],
						'notes'					=> $model['attributes']['name'].'<br/>Auto generated dari process log.',
						'start'					=> $model['attributes']['on'],
						'end'					=> $model['attributes']['on'],
						'quota'					=> -1
				]);

				$pworkleave->Person()->associate($person);

				if(!$pworkleave->save())
				{
					$model['errors'] 	= $pworkleave->getError();

					return false;
				}
			}
		}
	}

}
