<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\FingerPrint;
use App\Models\Person;
use App\Models\Branch;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class FingerPrintTableSeeder extends Seeder
{
	function run()
	{

		DB::table('finger_prints')->truncate();
		try
		{

		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}