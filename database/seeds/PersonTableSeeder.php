<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organisation;
use App\Models\Person;
use Faker\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PersonTableSeeder extends Seeder
{
	function run()
	{
		DB::table('persons')->truncate();
		$total_orgs 								= Organisation::count();
		$faker 										= Factory::create();
		$gender 									= ['male', 'female'];
		$prefix 									= ['Prof.', 'Dr.', 'Ir.'];
		$suffix 									= ['MT.', 'MSc.', 'BSc.', 'MSi.', 'BSi.', 'SE.', 'PhD.', 'SH.', 'SKom.', 'ST.', 'BA.'];
		try
		{
			foreach(range(1, 50) as $index)
			{
				$data = new Person;
				$data->fill([
					'uniqid'						=> $index,
					'username'						=> 'user'.$index,
					'name'							=> $faker->name,
					'prefix_title'					=> $prefix[rand(0,2)],
					'suffix_title'					=> $suffix[rand(0,10)],
					'place_of_birth'				=> $faker->city,
					'date_of_birth' 				=> $faker->date($format = 'Y-m-d', $max = 'now'), 
					'gender' 						=> $gender[rand ( 0 , 1 )],
					'password'						=> Hash::make('admin'),
					'last_password_updated_at' 		=> date('Y-m-d H:i:s')
				]);

				$organisation 						= Organisation::find(rand(1,$total_orgs));
				
				$data->organisation()->associate($organisation);

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