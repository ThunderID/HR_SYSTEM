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
			$actual_start_status 		= '';
			$actual_end_status 			= '';

			if($model->margin_start==0 && $model->margin_end==0)
			{
				$actual_start_status 	= 'AS';
			}
			elseif($model->margin_start>=0)
			{
				$actual_start_status 	= 'HB';
			}
			else
			{
				$actual_start_status 	= 'HC';
			}
			
			if($model->margin_start==0 && $model->margin_end==0)
			{
				$actual_end_status 		= 'AS';
			}
			elseif($model->margin_end>=0)
			{
				$actual_end_status 		= 'HB';
			}
			else
			{
				$actual_end_status 		= 'HC';
			}

			$model->fill(['actual_end_status' => $actual_end_status, 'actual_start_status' => $actual_start_status]);


			//save modfied status
			if(isset($model->getDirty()['modified_start_status']))
			{
				if(strtoupper($model->actual_start_status)=='HB')
				{
					$errors->add('modified', 'Tidak dapat mengubah status HB kedatangan karyawan');
					
					return false;
				}
				elseif(strtoupper($model->actual_start_status)=='HC' && !in_array(strtoupper($model->modified_start_status), ['HT', 'HD', 'HC']))
				{
					$errors->add('modified', 'Status untuk kedatangan cacat tidak valid.');
					
					return false;
				}
				elseif(strtoupper($model->actual_start_status)=='AS' && !in_array(strtoupper($model->modified_start_status), ['DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'AS']))
				{
					$errors->add('modified', 'Status untuk absensi tidak valid.');
					
					return false;
				}
			
				$model->fill(['modified_start_at' => date('Y-m-d H:i:s', strtotime('now'))]);
			}

			if(isset($model->getDirty()['modified_end_status']))
			{
				if(strtoupper($model->actual_end_status)=='HB')
				{
					$errors->add('modified', 'Tidak dapat mengubah status HB kepulangan karyawan');

					return false;
				}
				elseif(strtoupper($model->actual_end_status)=='HC' && !in_array(strtoupper($model->modified_end_status), ['HP', 'HD', 'HC']))
				{
					$errors->add('modified', 'Status untuk kepulangan cacat tidak valid.');
					
					return false;
				}
				elseif(strtoupper($model->actual_end_status)=='AS' && !in_array(strtoupper($model->modified_end_status), ['DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'AS']))
				{
					$errors->add('modified', 'Status untuk absensi tidak valid.');
					
					return false;
				}
			
				$model->fill(['modified_end_at' => date('Y-m-d H:i:s', strtotime('now'))]);
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
