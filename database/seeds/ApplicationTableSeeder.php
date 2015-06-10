<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Application;
use App\Models\Chart;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class ApplicationTableSeeder extends Seeder
{
	function run()
	{
		DB::table('applications')->truncate();
		DB::table('authentications')->truncate();

		$app 										= ['web', 'tracker', 'fingerprint'];
		try
		{
			foreach(range(0, count($app)-1) as $index)
			{
				$data 								= new Application;
				$data->fill([
					'name'							=> $app[$index],
				]);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}