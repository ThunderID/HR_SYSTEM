<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\PersonWorkleave;
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
			$model['errors'] 			= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		if(strtoupper($model->actual_status)=='AS' && (in_array(strtoupper($model->modified_status), ['CN','CI','CB'])))
		{
			//check if pw on that day were provided
			$pwM 						= PersonWorkleave::personid($model['attributes']['person_id'])->ondate([date('Y-m-d', strtotime($model['attributes']['on'])), date('Y-m-d', strtotime($model['attributes']['on']))])->status('CN')->quota(false)->first();
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

				$pworkleave 			= new PersonWorkleave;
				$pworkleave->fill([
						'work_id'				=> $model['attributes']['work_id'],
						'person_workleave_id'	=> $pwP->id,
						'created_by'			=> $model['attributes']['created_by'],
						'name'					=> 'Pengambilan '.$pwP->name,
						'status'				=> $model['attributes']['status'],
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
		$pworkleaves 					= PersonWorkleave::$personid($model['attributes']['person_id'])->ondate([$model['attributes']['on'], date('Y-m-d', strtotime($model['attributes']['on'].' + 1 day'))])->status(strtoupper($model['attributes']['modified_status']))->get();
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
