<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use \DB;

class MaritalStatusTableSeeder extends Seeder
{
	function run()
	{
		DB::table('marital_statuses')->truncate();
		$total_person 								= Person::count();
		try
		{
			$status 								= ['tk', 'k-0', 'k-1', 'k-2'];

			foreach(range(1, $total_person) as $index)
			{
				$data 								= Person::find($index);

				$maritalstatus 						= Person::find(rand(1,$total_person));

				if (!$data->maritalstatuses()->save($maritalstatus, ['status' => $status[rand(0,3)]]))
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