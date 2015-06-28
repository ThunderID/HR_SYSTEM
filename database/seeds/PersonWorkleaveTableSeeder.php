<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workleave;
use App\Models\PersonWorkleave;
use App\Models\Person;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class PersonWorkleaveTableSeeder extends Seeder
{
	function run()
	{

		DB::table('persons_workleaves')->truncate();
		$faker 										= Factory::create();
		$persons 									= Person::count();
		$workleaves 								= Workleave::count();
		try
		{
			foreach(range(1, $persons) as $index)
			{
				$workleave 							= rand(1, $workleaves);
				$person 							= Person::find($index);

				$data 								= new PersonWorkleave;
				$data->fill([
					'person_id'						=> 1,
					'workleave_id'					=> $workleave,
					'start'							=> date('Y-m-d',strtotime('first day of january 2015')),
					'end'							=> date('Y-m-d',strtotime('last day of december 2015')),
					'is_default'					=> true,
				]);

				$data->Person()->associate($person);

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