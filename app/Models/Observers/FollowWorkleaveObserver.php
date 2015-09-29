<?php namespace App\Models\Observers;

use DB, Validator, Event;
use App\Events\CreateRecordOnTable;

use App\Models\Work;
use App\Models\ChartWorkleave;

use \Illuminate\Support\MessageBag as MessageBag;
use Carbon\Carbon;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class FollowWorkleaveObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
		
			//cek riwayat kerja
			if(isset($model['attributes']['workleave_id']))
			{
				$errors 					= new MessageBag;
				$work 						= Work::active(true)->id($model['attributes']['work_id'])->first();

				if($work)
				{
					$prev_work 				= Work::active(false)->personid($work->person_id)->startbefore($work->start)->orderby('start', 'desc')->get();
					
					$work_start 			= $work->start;

					foreach ($prev_work as $key2 => $value2) 
					{
						if($key2 > 0)
						{
							$prev_end 		= new Carbon($prev_work[$key2-1]->end);
							$date_diff 		= $prev_end->diffInDays(new Carbon($value2->start));

							//if end of prev work not same day of next start (idle), then start of work must be next start
							if($date_diff > 1)
							{
								$work_start = $value2->start;
							}
							//else if end of prev work is same day of next start (idle), then start of work must be first work start
						}
						else
						{
							$work_start 	= $value2->start;
						}
					}

				
					$cwleave 				= ChartWorkleave::chartid($work['chart_id'])->workleaveid($model['attributes']['workleave_id'])->withattributes(['workleave'])->first();

					if($cwleave && (date('Y-m-d', strtotime($work_start)) <= date('Y-m-d', strtotime($cwleave->rules))) && (!$work->FollowWorkleave || $cwleave->workleave_id != $work->FollowWorkleave->workleave_id))
					{
						// dd(1);
						return true;
					}
					elseif(!isset($model->getDirty()['workleave_id']))
					{
						dd($model->getDirty());
						$errors->add('CWleave', 'Tidak dapat mengubah kuota cuti tahunan.');

						$model['errors']		= $errors;

						return false;
					}

				}
				else
				{
					
					$errors->add('CWleave', 'Tidak dapat mengubah kuota cuti untuk posisi dengan tanggal berakhir.');

					$model['errors']			= $errors;

					return false;
				}
			}

			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}

	}

	public function created($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menambah Quota Cuti Tahunan';
		$attributes['notes'] 				= 'Menambah Quota Cuti Tahunan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'delete';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function updated($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Mengubah Quota Cuti Tahunan ';
		$attributes['notes'] 				= 'Mengubah Quota Cuti Tahunan '.' pada '.date('d-m-Y');
		$attributes['old_attribute'] 		= json_encode($model->getOriginal());
		$attributes['new_attribute'] 		= json_encode($model->getAttributes());
		$attributes['action'] 				= 'save';

		Event::fire(new CreateRecordOnTable($attributes));
	}

	public function deleted($model)
	{
		$attributes['record_log_id'] 		= $model->id;
		$attributes['record_log_type'] 		= get_class($model);
		$attributes['name'] 				= 'Menghapus Quota Cuti Tahunan';
		$attributes['notes'] 				= 'Menghapus Quota Cuti Tahunan'.' pada '.date('d-m-Y');
		$attributes['action'] 				= 'restore';

		Event::fire(new CreateRecordOnTable($attributes));
	}
}
