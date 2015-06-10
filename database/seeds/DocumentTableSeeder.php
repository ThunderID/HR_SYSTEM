<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\Models\Template;
use App\Models\Organisation;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class DocumentTableSeeder extends Seeder
{
	function run()
	{
		DB::table('documents')->truncate();
		DB::table('templates')->truncate();
		$name 										= [
														'surat peringatan', 
														'kontrak kerja', 
														'penilaian kinerja', 
														'pendidikan formal', 
														'pendidikan non formal', 
														'ktp',
														'bpjs',
														'npwp',
														'bank',
														'reksa dana',
														];
		$tag 										= [
														'SP', 
														'Kontrak', 
														'Appraisal', 
														'Pendidikan', 
														'Pendidikan', 
														'Identitas',
														'Pajak',
														'Pajak',
														'Akun',
														'Akun',
														];
		$template 									= [
														[
															'nama',
															'tanggal',
															'content',
														], 
														[
															'nama',
															'tanggal',
															'content',
														], 
														[
															'nama',
															'tanggal',
															'content'
														], 
														[
															'nama pendidikan',
															'institusi',
															'bidang studi',
															'tanggal masuk',
															'tanggal lulus',
															'grade'
														], 
														[
															'nama seminar/training',
															'penyelenggara',
															'bidang',
															'tanggal mulai',
															'tanggal selesai'
														], 
														[
															'ktp',
														], 
														[
															'bpjs',
														], 
														[
															'npwp',
														], 
														[
															'nama bank',
															'cabang',
															'produk akun',
															'nomor akun',
														], 
														[
															'nama reksa dana',
															'produk reksa dana',
															'nomor reksa dana',
														], 
													];
		$organisation 								= Organisation::find(1);
		try
		{
			foreach(range(0, count($name)-1) as $index)
			{
				if($index>4 && $index<7)
				{
					$required 						= true;
				}
				else
				{
					$required 						= false;
				}
				$data = new Document;
				$data->fill([
					'name'							=> $name[$index],
					'tag'							=> $tag[$index],
					'is_required'					=> $required,
				]);
				$data->organisation()->associate($organisation);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}

				foreach ($template[$index] as $key => $value) 
				{
					$doc_template[$key] 			= new Template;
					$doc_template[$key]->fill([
						'field'						=> $value,
						'type'						=> 'string',
					]);


				}
				$data->templates()->saveMany($doc_template);
				unset($doc_template);
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}