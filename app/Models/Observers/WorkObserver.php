<?php namespace App\Models\Observers;

use DB, Validator;
use App\Models\Work;
use App\Models\Chart;
use App\Models\Person;
use App\Models\Finger;
use App\Models\Follow;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Saved						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class WorkObserver 
{
	public function saving($model)
	{
		$validator 						= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['chart_id']) && !is_null($model['attributes']['chart_id']) && $model['attributes']['chart_id']!=0)
			{
				$validator 			= Validator::make($model['attributes'], ['calendar_id'				=> 'required_without:position|required_if:status,contract,trial,internship,permanent'], ['calendar_id.required_without' => 'Kalender kerja tidak boleh kosong. Pastikan posisi memiliki kalender kerja yang dimaksud.']);
				if (!$validator->passes())
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}

				$validator 				= Validator::make($model['attributes'], ['chart_id' => 'exists:charts,id']);
				if ($validator->passes())
				{
					if((!isset($model['attributes']['end']) || is_null($model['attributes']['end'])) && isset($model['attributes']['person_id']) && $model['attributes']['person_id']!=0 && !count($model->person->finger))
					{
						$finger 		= new Finger;
						
						$person			= Person::find($model['attributes']['person_id']);

						$finger->person()->associate($person);

						$finger->save();

					}
					return true;
				}
				else
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}
			}

			if(isset($model['attributes']['calendar_id']) && !is_null($model['attributes']['calendar_id']) && $model['attributes']['calendar_id']!=0)
			{
				$validator 				= Validator::make($model['attributes'], ['calendar_id' => 'exists:calendars,id']);
				if ($validator->passes())
				{
					$check 				= Follow::CalendarID($model['attributes']['calendar_id'])->ChartID($model['attributes']['chart_id'])->get();
					if(count($check))
					{
						return true;
					}

					$errors 			= new MessageBag;
					$errors->add('notmatch', 'Posisi tidak memiliki jadwal tersebut.');

					$model['errors'] 	= $errors;
					
					return false;
				}
				else
				{
					$model['errors'] 	= $validator->errors();

					return false;
				}
				
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
		if(isset($model['attributes']['chart_id']) && $model['attributes']['chart_id']!=0)
		{
			$current_employee 			= Work::where('chart_id',$model['attributes']['chart_id'])->whereNull('end')->orwhere('end', '>=', 'NOW()')->count();
			$updated 					= Chart::where('id', $model['attributes']['chart_id'])->update(['current_employee' => $current_employee]);
		}
		return true;
	}

	public function updating($model)
	{
		if(strtolower($model->getOriginal()['status'])=='admin' && (isset($model->getDirty()['status']) || isset($model->getDirty()['end'])))
		{
			$errors 			= new MessageBag;
			$errors->add('admin', 'Tidak dapat mengubah informasi terkait pekerjaan sebagai admin.');

			$model['errors'] 	= $errors;
					
			return false;
		}
		return true;
	}

	public function deleting($model)
	{
		if($model->chart && $model->chart->count() && $model['attributes']['status']!='admin')
		{
			$model['errors'] 	= ['Tidak dapat menghapus pekerjaan saat ini. Silahkan tandai sebagai pekerjaan yang sudah berakhir dengan catatan khusus.'];

			return false;
		}
	}
}
