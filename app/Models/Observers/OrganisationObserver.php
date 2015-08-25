<?php namespace App\Models\Observers;

use \Validator;
use \App\Models\Organisation;
use \App\Models\Branch;
use \App\Models\Chart;
use \App\Models\Work;
use \App\Models\Policy;
use \App\Models\Document;
use \App\Models\Template;

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
				$types 									= ['passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle', 'extendsworkleave', 'extendsmidworkleave', 'firstacleditor', 'secondacleditor'];
				$values 								= ['- 3 months', '1', '1', '2', '2', '2', '- 1 month', '- 5 days', '900', '3600','7200', '+ 3 months', '+ 1 year + 3 months', '- 1 month', '- 5 days'];
				foreach(range(0, count($types)-1) as $key => $index)
				{
					$policy 							= new Policy;
					$policy->fill([
						'type'							=> $types[$key],
						'value'							=> $values[$key],
						'started_at'					=> date('Y-m-d'),
					]);

					$policy->organisation()->associate($model);

					if (!$policy->save())
					{
						$model['errors'] 				= $policy->getError();

						return false;
					}
				}

				$name 									= 
															[
																'KTP',
																'Akun Bank',
																'NPWP',
																'BPJS Ketenagakerjaan',
																'BPJS Kesehatan',

																'Surat Peringatan I', 
																'Surat Peringatan II', 
																'Surat Peringatan III', 
																'Penilaian Kinerja I', 
																'Penilaian Kinerja II', 
																'Penilaian Kinerja III', 
															];

				$tag 									= 
															[
																'Identitas',
																'Akun',
																'Pajak',
																'Pajak',
																'Pajak',

																'SP', 
																'SP', 
																'SP', 
																'Apraisal', 
																'Apraisal', 
																'Apraisal', 
															];
				$template 								= 
															[
																[
																	'Nomor KTP',
																	'Alamat',
																	'Kota',
																	'Agama',
																	'Status Kawin',
																	'Kewarganegaraan',
																	'Berlaku Hingga',
																], 
																[
																	'Nomor Rekening',
																	'Nama Bank',
																	'Nama Nasabah',
																], 
																[
																	'NPWP',
																], 
																[
																	'Nomor BPJS-TK',
																], 
																[
																	'Nomor BPJS-K',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Surat Peringatan',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Surat Peringatan',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Surat Peringatan',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Penilaian Kinerja',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Penilaian Kinerja',
																], 
																[
																	'Nomor Surat',
																	'Tanggal',
																	'Isi Penilaian Kinerja',
																], 
															];

				$type 									= 	[
																[
																	'string',
																	'text',
																	'string',
																	'string',
																	'string',
																	'string',
																	'date',
																], 
																[
																	'string',
																	'string',
																	'string',
																], 
																[
																	'string',
																], 
																[
																	'string',
																], 
																[
																	'string',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
																[
																	'string',
																	'date',
																	'text',
																], 
															];

				foreach(range(0, count($name)-1) as $index)
				{
					if($index<4)
					{
						$required 						= true;
					}
					else
					{
						$required 						= false;
					}
					$document = new Document;
					$document->fill([
						'name'							=> $name[$index],
						'tag'							=> $tag[$index],
						'is_required'					=> $required,
					]);
					$document->organisation()->associate($model);

					if (!$document->save())
					{
						$model['errors'] 				= $document->getError();

						return false;
					}

					foreach ($template[$index] as $key => $value) 
					{
						$templates[$key] 				= new Template;
						$templates[$key]->fill([
							'field'						=> $value,
							'type'						=> $type[$index][$key],
						]);


					}
					$document->templates()->saveMany($templates);
					unset($templates);
				}

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
			$validator 			= Validator::make($model['attributes'], ['code' => 'unique:organisations,code,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : '')], ['code.unique' => 'Kode sudah terpakai']);

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

		if($model->persons()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki personalia. Silahkan hapus data personalia terlebih dahulu.'];

			return false;
		}

		$personsdocs 			= Document::organisationid($model['attributes']['id'])->wherehas('persons', function($q){$q;})->count();

		if($personsdocs)
		{
			$model['errors'] 	= ['Tidak dapat menghapus organisasi yang memiliki dokumen personalia. Silahkan hapus data dokumen personalia terlebih dahulu.'];

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
					$work 	 				= new Work;
					$delete 				= $work->find($value3['pivot']['id']);

					if($delete && !$delete->delete())
					{
						$model['errors']	= $delete->getError();
						
						return false;
					}
				}

				$chart 	 					= new Chart;
				$delete 					= $chart->id($value2->id)->first();
				if($delete && !$delete->delete())
				{
					$model['errors']		= $delete->getError();
				
					return false;
				}
			}

			$branch 	 					= new Branch;
			$delete 						= $branch->id($value->id)->first();
			if($delete && !$delete->delete())
			{
				$model['errors']			= $delete->getError();

				return false;
			}
		}

		foreach ($model->policies as $key => $value) 
		{
			$policy 	 					= new Policy;
			$delete 						= $policy->id($value->id)->first();
			if($delete && !$delete->delete())
			{
				$model['errors']			= $delete->getError();

				return false;
			}
		}

		foreach ($model->documents as $key => $value) 
		{
			foreach ($value->templates as $key2 => $value2) 
			{
				$template 	 				= new Template;
				$delete 					= $template->id($value2->id)->first();
				if($delete && !$delete->delete())
				{
					$model['errors']		= $delete->getError();
				
					return false;
				}
			}

			$document 	 					= new Document;
			$delete 						= $document->id($value->id)->first();
			if($delete && !$delete->delete())
			{
				$model['errors']			= $delete->getError();

				return false;
			}
		}

		return true;
	}
}
