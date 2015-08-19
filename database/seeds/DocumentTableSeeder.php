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
		DB::table('tmp_documents')->truncate();
		DB::table('tmp_templates')->truncate();
		$name 										= [
														'KTP',
														'Akun Bank',
														'NPWP',
														'BPJS Ketenagakerjaan',
														'BPJS Kesehatan',
														
														'Akun Reksa Dana',
														'SD', 
														'SMP', 
														'SMA', 
														'Universitas', 
														'Seminar', 
														'Training', 
														
														'Kontrak Kerja', 
														'Surat Peringatan I', 
														'Surat Peringatan II', 
														'Surat Peringatan III', 
														'Penilaian Kinerja I', 
														'Penilaian Kinerja II', 
														'Penilaian Kinerja III', 
														];
		$tag 										= [
														'Identitas',
														'Akun',
														'Pajak',
														'Pajak',
														'Pajak',

														'Akun',
														'Pendidikan', 
														'Pendidikan', 
														'Pendidikan', 
														'Pendidikan', 
														'Pendidikan', 
														'Pendidikan', 
														
														'Kontrak', 
														'SP', 
														'SP', 
														'SP', 
														'Apraisal', 
														'Apraisal', 
														'Apraisal', 
														];
		$template 									= [
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
															'Nomor Reksa Dana',
															'Nama Reksa Dana',
															'Nama Pemilik Akun',
														], 
														[
															'Institusi',
															'Tanggal Masuk',
															'Tanggal Lulus',
															'Nilai',
														], 
														[
															'Institusi',
															'Tanggal Masuk',
															'Tanggal Lulus',
															'Nilai',
														], 
														[
															'Institusi',
															'Tanggal Masuk',
															'Tanggal Lulus',
															'Nilai',
														], 
														[
															'Institusi',
															'Tanggal Masuk',
															'Tanggal Lulus',
															'IPK',
														], 
														[
															'Nama Seminar',
															'Penyelenggara',
															'Bidang',
															'Tanggal Mulai',
															'Tanggal Selesai'
														], 
														[
															'Nama Training',
															'Penyelenggara',
															'Bidang',
															'Tanggal Mulai',
															'Tanggal Selesai',
															'Predikat',
														], 
														[
															'Nomor Surat',
															'Tanggal',
															'Isi Surat Perjanjian',
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

				$type 								= [
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
															'string',
															'string',
														], 
														[
															'string',
															'date',
															'date',
															'numeric',
														], 
														[
															'string',
															'date',
															'date',
															'numeric',
														], 
														[
															'string',
															'date',
															'date',
															'numeric',
														], 
														[
															'string',
															'date',
															'date',
															'numeric',
														], 
														[
															'string',
															'string',
															'string',
															'date',
															'date'
														], 
														[
															'string',
															'string',
															'string',
															'date',
															'date',
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
														[
															'string',
															'date',
															'text',
														], 
													];
		$organisation 								= Organisation::find(1);
		try
		{
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
						'type'						=> $type[$index][$key],
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