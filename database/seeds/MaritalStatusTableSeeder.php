<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Relative;
use App\Models\MaritalStatus;
use \DB;

class MaritalStatusTableSeeder extends Seeder
{
	function run()
	{
		DB::table('marital_statuses')->truncate();
		$total_relative 							= Relative::whereIn('relationship', ['spouse', 'child', 'partner'])->get();
		try
		{
			$status 								= ['partner' => 'tk', 'spouse' => 'k-0', 'child' => 'k-1'];

			foreach(range(0, count($total_relative)-1) as $index)
			{
				$person 							= Person::find($total_relative[$index]->person_id);

				$data 								= new MaritalStatus;
				$data->fill([
						'status' 					=> $status[strtolower($total_relative[$index]->relationship)],
						'on'						=> date('Y-m-d H:i:s')
					]);

				$data->person()->associate($person);

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