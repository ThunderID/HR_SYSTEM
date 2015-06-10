<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finger;
use App\Models\Person;
use App\Models\Chart;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class FingerTableSeeder extends Seeder
{
	function run()
	{

		// DB::table('fingers')->truncate();

		try
		{
			foreach(range(1, 1) as $index)
			{
				
			} 

		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}