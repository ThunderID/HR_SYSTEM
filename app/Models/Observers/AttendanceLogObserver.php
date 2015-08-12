<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\AttendanceLog;
use App\Models\AttendanceDetail;
use App\Models\ProcessLog;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * ---------------------------------------------------------------------- */

class AttendanceLogObserver 
{
	public function saving($model)
	{
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
			$model['errors'] 					= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		$on 									= date('Y-m-d', strtotime($model->processlog->on));
		if(!is_null($model->settlement_at))
		{
			$errors 							= new MessageBag;
			$errors->add('settle', 'Tidak dapat mengubah status yang sudah settle');

			$model['errors'] 					= $validator->errors();
			
			return false;
		}
		elseif(strtoupper($model->actual_status)=='AS' && (in_array(strtoupper($model->modified_status), ['CN','CI','CB'])))
		{
			//check if pw on that day were provided
			$pwM 								= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status(strtoupper($model->modified_status))->quota(false)->first();
			if($pwM)
			{
				return true;
			}
			//check if pw on that day were not provided
			else
			{
				$pwP 							= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status('CN')->quota(true)->first();
				if(!$pwP)
				{
					$errors->add('Workleave', 'Quota Cuti Tidak Tersedia. Jika ingin menambahkan hutang cuti, silahkan tambahkan quota cuti tahun berikutnya.');
					
					$model['errors'] 			= $errors;

					return false;
				}

				$person 						= Person::find($model->processlog->person_id);

				$pworkleave 					= new PersonWorkleave;
				$pworkleave->fill([
						'work_id'				=> $model->processlog->work_id,
						'person_workleave_id'	=> $pwP->id,
						'created_by'			=> $model['attributes']['modified_by'],
						'name'					=> 'Pengambilan '.$pwP->name,
						'status'				=> $model['attributes']['modified_status'],
						'notes'					=> $model['attributes']['notes'],
						'start'					=> $model->processlog->on,
						'end'					=> $model->processlog->on,
						'quota'					=> -1
				]);

				$pworkleave->Person()->associate($person);

				if(!$pworkleave->save())
				{
					$model['errors'] 			= $pworkleave->getError();

					return false;
				}
			}
		}
		//check if current status was as or ul and cut workleave off (sync with policies bout cutting). to be considered : cut workleave when hc
		elseif(strtoupper($model->actual_status)=='AS' && (in_array(strtoupper($model->modified_status), ['AS','UL'])))
		{
			//check if pw on that day were provided
			$pwM 								= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status(strtoupper($model->modified_status))->quota(false)->first();
			if($pwM)
			{
				return true;
			}
			//check if pw on that day were not provided
			else
			{
				$pwP 							= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status('CN')->quota(true)->first();
				if(!$pwP)
				{
					return true;
				}

				$alog 							= new AttendanceLog;
				$alog->fill([
					'margin_start'				=> $margin_start,
					'margin_end'				=> $margin_end,
					'count_status'				=> $count_status,
					'actual_status'				=> $actual_status,
					'modified_status'			=> 'CN',
					'modified_at'				=> $modified_at,
					'modified_by'				=> $modified_by,
					'notes'						=> 'Auto generated dari attendance log, sebagai bentuk sanksi.',
				]);

				$processlog 					= ProcessLog::find($model['attributes']['process_log_id']);

				$alog->ProcessLog()->associate($processlog);

				if(!$alog->save())
				{
					$model['errors'] 			= $alog->getError();

					return false;
				}

				$pw 							= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status('CN')->quota(true)->first();
				
				//save detailed attendance detail
				$adetail 						= new AttendanceDetail;
				$adetail->fill(['attendance_log_id', $alog->id]);

				$adetail->PersonWorkleave()->associate($pw);

				if(!$adetail->save())
				{
					$model['errors'] 			= $adetail->getError();

					return false;
				}
			}
		}

		//check if current status wasn't workleave but previously marked as workleave
		$prev_data 								= AttendanceLog::processlogid($model['attributes']['process_log_id'])->orderBy('created_at', 'desc')->first();
		if($prev_data->modified_status && in_array($prev_data, ['CN', 'CB']) && !in_array($model['attributes']['modified_status'], ['CN', 'CB']))
		{
			$pwM 								= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status(strtoupper($prev_data->modified_status))->quota(false)->first();

			if(!$pwM->delete())
			{
				$model['errors']				= $data->getError();
				
				return false;
			}

			return true;
		}

	}
}
