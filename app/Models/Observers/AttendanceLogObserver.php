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
			$errors 							= new MessageBag;
			
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

			if(isset($model['attributes']['process_log_id']))
			{
				//modify count status
				$prev_data 								= ProcessLog::personid($model->processlog['person_id'])->notid($model['attributes']['process_log_id'])->lastattendancelog(null)->orderby('on', 'desc')->first();

				//check prev data if modified and current data is modify
				if($prev_data && isset($prev_data['attendancelogs'][0]) && ($prev_data['attendancelogs'][0]['modified_status'] != '' && $model['attributes']['modified_status']!='') && (strtoupper($prev_data['attendancelogs'][0]['modified_status']) == strtoupper($model['attributes']['modified_status'])))
				{
					$count 								= $prev_data['attendancelogs'][0]['count_status'] + 1;
				}
				elseif($prev_data && isset($prev_data['attendancelogs'][0]) && ($prev_data['attendancelogs'][0]['modified_status'] != '' && $model['attributes']['modified_status']=='') && (strtoupper($prev_data['attendancelogs'][0]['modified_status']) == strtoupper($model['attributes']['actual_status'])))
				{
					$count 								= $prev_data['attendancelogs'][0]['count_status'] + 1;
				}
				elseif($prev_data && isset($prev_data['attendancelogs'][0]) && ($prev_data['attendancelogs'][0]['modified_status'] == '' && $model['attributes']['modified_status']=='') && (strtoupper($prev_data['attendancelogs'][0]['actual_status']) == strtoupper($model['attributes']['actual_status'])))
				{
					$count 								= $prev_data['attendancelogs'][0]['count_status'] + 1;
				}
				elseif($prev_data && isset($prev_data['attendancelogs'][0]) && ($prev_data['attendancelogs'][0]['modified_status'] == '' && $model['attributes']['modified_status']!='') && (strtoupper($prev_data['attendancelogs'][0]['actual_status']) == strtoupper($model['attributes']['modified_status'])))
				{
					$count 								= $prev_data['attendancelogs'][0]['count_status'] + 1;
				}
				else
				{
					$count 								= 1;
				}

				$model->count_status 					= $count;
			}

			return true;
		}
		else
		{
			$model['errors'] 						= $validator->errors();

			return false;
		}
	}

	public function saved($model)
	{
		$on 									= date('Y-m-d', strtotime($model->processlog['on']));
		if(strtoupper($model->actual_status)=='AS' && (in_array(strtoupper($model->modified_status), ['CN','CI','CB'])))
		{
			//check if pw on that day were provided
			$pwM 								= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, $on])->status(strtoupper($model->modified_status))->quota(false)->first();
			if($pwM)
			{
				return true;
			}
			//check if pw on that day were not provided
			else
			{
				$pwP 							= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status('CN')->quota(true)->first();

				$person 						= Person::find($model->processlog->person_id);

				$pworkleave 					= new PersonWorkleave;
				$pworkleave->fill([
						'work_id'				=> $model->processlog->work_id,
						'person_workleave_id'	=> $pwP->id,
						'created_by'			=> $model['attributes']['modified_by'],
						'name'					=> 'Pengambilan '.$pwP->name,
						'status'				=> $model['attributes']['modified_status'],
						'notes'					=> (isset($model['attributes']['notes']) ? $model['attributes']['notes'] : ''),
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

				$pwP2 							= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, null])->status('CN')->quota(true)->sum('quota');
				if($pwP)
				{
					$pwP3 						= PersonWorkleave::parentid($pwP->id)->status(['CN', 'CB'])->quota(false)->sum('quota');
				}

				if(!$pwP || (isset($pwP3) && ($pwP2 + $pwP3) <= 0 ))
				{
					$alog 						= new AttendanceLog;
					$alog->fill([
						'margin_start'			=> $model['attributes']['margin_start'],
						'margin_end'			=> $model['attributes']['margin_end'],
						'count_status'			=> $model['attributes']['count_status'],
						'actual_status'			=> $model['attributes']['actual_status'],
						'modified_status'		=> 'UL',
						'modified_at'			=> $model['attributes']['modified_at'],
						'modified_by'			=> $model['attributes']['modified_by'],
						'notes'					=> 'Auto generated dari attendance log, karena tidak ada cuti.',
					]);

					$processlog 				= ProcessLog::find($model['attributes']['process_log_id']);

					$alog->ProcessLog()->associate($processlog);

					if(!$alog->save())
					{
						$model['errors'] 		= $alog->getError();

						return false;
					}

					return true;
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
					'margin_start'				=> $model['attributes']['margin_start'],
					'margin_end'				=> $model['attributes']['margin_end'],
					'count_status'				=> $model['attributes']['count_status'],
					'actual_status'				=> $model['attributes']['actual_status'],
					'modified_status'			=> 'CN',
					'modified_at'				=> $model['attributes']['modified_at'],
					'modified_by'				=> $model['attributes']['modified_by'],
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
		if(isset($model['attributes']['process_log_id']))
		{
			$prev_data 								= AttendanceLog::processlogid($model['attributes']['process_log_id'])->orderBy('created_at', 'asc')->first();

			if($prev_data && in_array($prev_data->modified_status, ['CN', 'CB']) && !in_array($model['attributes']['modified_status'], ['CN', 'CB']))
			{
				$pwM 								= PersonWorkleave::personid($model->processlog->person_id)->ondate([$on, $on])->status(strtoupper($prev_data->modified_status))->quota(false)->first();

				if(!$pwM->delete())
				{
					$model['errors']				= $data->getError();
					
					return false;
				}

				return true;
			}
		}

	}
}
