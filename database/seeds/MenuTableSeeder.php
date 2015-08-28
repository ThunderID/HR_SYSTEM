<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Application;
use App\Models\Menu;
use App\Models\Chart;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
	function run()
	{
		DB::table('tmp_menus')->truncate();
		$menus 										= 
														[
															'Lihat Organisasi',
															'Tambah Organisasi',
															'Ubah Organisasi',
															'Hapus Organisasi',

															'Lihat Cabang',
															'Tambah Cabang',
															'Ubah Cabang',
															'Hapus Cabang',

															'Lihat Struktur Organisasi',
															'Tambah Struktur Organisasi',
															'Ubah Struktur Organisasi',
															'Hapus Struktur Organisasi',

															'Lihat Template Kalender Kerja',
															'Tambah Template Kalender Kerja',
															'Ubah Template Kalender Kerja',
															'Hapus Template Kalender Kerja',

															'Lihat API',
															'Tambah API',
															'Ubah API',
															'Hapus API',

															'Lihat Finger of The Day',
															'Tambah Finger of The Day',
															'Ubah Finger of The Day',
															'Hapus Finger of The Day',

															'Lihat Kontak Cabang',
															'Tambah Kontak Cabang',
															'Ubah Kontak Cabang',
															'Hapus Kontak Cabang',

															'Lihat Kalender',
															'Tambah Kalender',
															'Ubah Kalender',
															'Hapus Kalender',

															'Lihat Kostum Jadwal',
															'Tambah Kostum Jadwal',
															'Ubah Kostum Jadwal',
															'Hapus Kostum Jadwal',

															'Lihat Template Cuti',
															'Tambah Template Cuti',
															'Ubah Template Cuti',
															'Hapus Template Cuti',

															'Lihat Template Dokumen',
															'Tambah Template Dokumen',
															'Ubah Template Dokumen',
															'Hapus Template Dokumen',

															'Lihat Idle',
															'Tambah Idle',
															'Ubah Idle',
															'Hapus Idle',

															'Lihat Aplikasi',
															'Tambah Aplikasi',
															'Ubah Aplikasi',
															'Hapus Aplikasi',

															'Lihat Menu',
															'Tambah Menu',
															'Ubah Menu',
															'Hapus Menu',

															'Lihat Auth Group',
															'Tambah Auth Group',
															'Ubah Auth Group',
															'Hapus Auth Group',

															'Lihat Data Personalia',
															'Tambah Data Personalia',
															'Ubah Data Personalia',
															'Hapus Data Personalia',

															'Lihat Penempatan Kerja Karyawan',
															'Tambah Penempatan Kerja Karyawan',
															'Ubah Penempatan Kerja Karyawan',
															'Hapus Penempatan Kerja Karyawan',

															'Lihat Otentikasi Karyawan',
															'Tambah Otentikasi Karyawan',
															'Ubah Otentikasi Karyawan',
															'Hapus Otentikasi Karyawan',

															'Lihat Jadwal Kerja Karyawan',
															'Tambah Jadwal Kerja Karyawan',
															'Ubah Jadwal Kerja Karyawan',
															'Hapus Jadwal Kerja Karyawan',

															'Lihat Pengaturan Cuti Karyawan',
															'Tambah Pengaturan Cuti Karyawan',
															'Ubah Pengaturan Cuti Karyawan',
															'Hapus Pengaturan Cuti Karyawan',

															'Lihat Dokumen Karyawan',
															'Tambah Dokumen Karyawan',
															'Ubah Dokumen Karyawan',
															'Hapus Dokumen Karyawan',

															'Lihat Informasi Kontak Karyawan',
															'Tambah Informasi Kontak Karyawan',
															'Ubah Informasi Kontak Karyawan',
															'Hapus Informasi Kontak Karyawan',

															'Lihat Data Kerabat Karyawan',
															'Tambah Data Kerabat Karyawan',
															'Ubah Data Kerabat Karyawan',
															'Hapus Data Kerabat Karyawan',

															'Lihat Laporan Aktivitas',
															'Tambah Laporan Aktivitas',
															'Ubah Laporan Aktivitas',
															'Hapus Laporan Aktivitas',

															'Lihat Laporan Kehadiran',
															'Tambah Laporan Kehadiran',
															'Ubah Laporan Kehadiran',
															'Hapus Laporan Kehadiran',

															'Ganti Password',

															'Lihat Record Log',
															'Tambah Record Log',
															'Ubah Record Log',
															'Hapus Record Log',
														];

				$tags 								= 
														[
															'Organisasi',
															'Organisasi',
															'Organisasi',
															'Organisasi',

															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',

															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',

															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',

															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',

															'Pengaturan Presensi',
															'Pengaturan Presensi',
															'Pengaturan Presensi',
															'Pengaturan Presensi',

															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',
															'Pengaturan Cabang',

															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',

															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',

															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',
															'Pengaturan Jadwal',

															'Pengaturan Dokumen',
															'Pengaturan Dokumen',
															'Pengaturan Dokumen',
															'Pengaturan Dokumen',

															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',

															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',

															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',
															'Pengaturan Aplikasi (hardware)',

															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',
															'Pengaturan Grup Otentikasi',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',
															'Pengaturan Data Karyawan',

															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',

															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',
															'Pengaturan Laporan',

															'Pengaturan Aplikasi',

															'Web Log',
															'Web Log',
															'Web Log',
															'Web Log',
														];

		$trackermenu 								= 
														[
															'Login App'
														];
		$fpmenu 									= 
														[
															'Login App'
														];
		try
		{
			foreach(range(0, count($menus)-1) as $index)
			{
				$data 								= new Menu;
				$data->fill([
					'name'							=> $menus[$index],
					'tag'							=> $tags[$index],
				]);

				$app 								= Application::find(1);
				
				$data->application()->associate($app);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}

			foreach(range(0, count($trackermenu)-1) as $index)
			{
				$data 								= new Menu;
				$data->fill([
					'name'							=> $trackermenu[$index],
					'tag'							=> 'Pengaturan Aplikasi',
				]);

				$app 								= Application::find(2);
				
				$data->application()->associate($app);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}

			// foreach(range(0, count($fpmenu)-1) as $index)
			// {
			// 	$data 								= new Menu;
			// 	$data->fill([
			// 		'name'							=> $fpmenu[$index],
			// 	]);

			// 	$app 								= Application::find(3);
				
			// 	$data->application()->associate($app);

			// 	if (!$data->save())
			// 	{
			// 		print_r($data->getError());
			// 		exit;
			// 	}
			// }
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}