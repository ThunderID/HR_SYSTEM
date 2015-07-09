<?php namespace App\Models\Observers;

use DB, Validator;
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
		$errors 						= new MessageBag;
		
		$validator 						= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			//save actual status for both start and end
			if(!isset($model['attributes']['actual_status']))
			{
				$actual_status 				= '';

				if($model->margin_start==0 && $model->margin_end==0)
				{
					$actual_status 			= 'AS';
				}
				elseif($model->margin_start>=0 && $model->margin_end>=0)
				{
					$actual_status 			= 'HB';
				}
				else
				{
					$actual_status 			= 'HC';
				}

				$model->fill(['actual_status' => $actual_status]);
			}
			//save modfied status
			if(isset($model->getDirty()['modified_status']))
			{
				if(strtoupper($model->actual_status)=='HB')
				{
					$errors->add('modified', 'Tidak dapat mengubah status dengan status awal HB');
					$model['errors'] 	= $errors;
					
					return false;
				}
				elseif(strtoupper($model->actual_status)=='HC' && !in_array(strtoupper($model->modified_status), ['HT', 'HD', 'HC', 'HP']))
				{
					$errors->add('modified', 'Status untuk kedatangan cacat tidak valid. Pilih diantara status berikut : HT, HD, HC, HP');
					$model['errors'] 	= $errors;
					
					return false;
				}
				elseif(strtoupper($model->actual_status)=='AS' && !in_array(strtoupper($model->modified_status), ['DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'AS']))
				{
					$errors->add('modified', 'Status untuk absensi tidak valid. Pilih diantara status berikut : DN, SS, SL, CN, CB, CI, UL, AS');
					
					$model['errors'] 	= $errors;
					
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

	public function deleting($model)
	{
		if(date('Y-m-d',strtotime($model['attributes']['on'])) <= date('Y-m-d'))
		{
			$model['errors'] 			= ['Tidak dapat menghapus log yang sudah lewat dari tanggal hari ini.'];

			return false;
		}
	}
}
