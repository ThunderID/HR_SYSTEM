<?php namespace App\Models\Observers;

use \Validator;
use \App\Models\Organisation;
use \App\Models\Branch;
use \App\Models\Chart;
use \App\Models\Work;
use \App\Models\Menu;

/* ----------------------------------------------------------------------
 * Event:
 * 	Created						
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class OrganisationObserver 
{
	public function created($model)
	{
		$branch 				= new Branch;
		$chart 					= new Chart;

		$branch->fill([
								'name' 					=> 'pusat',
		]);

		$branch->Organisation()->associate($model);

		if($branch->save())
		{
			$chart->fill([
								'name' 					=> 'system admin',
								'grade' 				=> 'A',
								'tag' 					=> 'admin',
								'min_employee' 			=> '1',
								'ideal_employee' 		=> '1',
								'max_employee' 			=> '1',
								'current_employee' 		=> '1',
			]);

			$chart->Branch()->associate($branch);

			if($chart->save())
			{
				return true;
			}

			$model['errors'] 	= $chart->getError();

			return false;
		}

		$model['errors'] 		= $branch->getError();

		return false;
	}

	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules'], ['name.required' => 'Nama organisasi tidak boleh kosong.', 'name.max' => 'Nama organisasi maksimal 255 karakter (termasuk spasi).']);

		if ($validator->passes())
		{
			$validator 			= Validator::make($model['attributes'], ['code' => 'unique:organisations,code,'.(isset($model['attributes']['id']) ? $model['attributes']['id'].'NULL,deleted_at' : 'NULL,deleted_at')], ['code.unique' => 'Kode sudah terpakai']);

			if ($validator->passes())
			{
				return true;
			}
			
			$model['errors'] 	= $validator->errors();

			return false;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function deleting($model)
	{
		//
		if($model['attributes']['id']==1)
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi ini. Silahkan mengubah data organisasi tanpa menghapus.'];

			return false;
		}

		if($model->calendars()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki data kalender. Silahkan hapus data kalender terlebih dahulu.'];

			return false;
		}

		if($model->workleaves()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki data cuti. Silahkan hapus data cuti terlebih dahulu.'];

			return false;
		}

		if($model->documents()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki data dokumen. Silahkan hapus data dokumen terlebih dahulu.'];

			return false;
		}

		$workers 				= Organisation::id($model['attributes']['id'])->onlyadmin(false)->first();
		
		if(count($workers))
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki pegawai.'];

			return false;
		}
		foreach ($model->branches as $key => $value) 
		{
			foreach ($value->charts as $key2 => $value2) 
			{
				foreach ($value2->works as $key3 => $value3) 
				{
					$work 	 	= new Work;
					$delete 	= $work->find($value3['pivot']['id']);

					if($delete && !$delete->delete())
					{
						$model['errors']	= $delete->getError();
						
						return false;
					}
				}

				$chart 	 		= new Chart;
				$delete 		= $chart->id($value2->id)->first();
				if($delete && !$delete->delete())
				{
					$model['errors']		= $delete->getError();
				
					return false;
				}
			}

			$branch 	 		= new Branch;
			$delete 			= $branch->id($value->id)->first();
			if($delete && !$delete->delete())
			{
				$model['errors']			= $delete->getError();

				return false;
			}
		}
		return true;
	}
}
