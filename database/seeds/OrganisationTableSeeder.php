<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organisation;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class OrganisationTableSeeder extends Seeder
{
	function run()
	{
		DB::table('organisations')->truncate();
		$faker 										= Factory::create();
		try
		{
			foreach(range(1, 1) as $index)
			{
				$data = new Organisation;
				$data->fill([
					'name'							=> $faker->company,
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