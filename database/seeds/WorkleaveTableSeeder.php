<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workleave;
use App\Models\Organisation;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class WorkleaveTableSeeder extends Seeder
{
	function run()
	{

		DB::table('tmp_workleaves')->truncate();
		$organisations 								= Organisation::get();
		// $workleaves 								= ['Cuti Tahunan', 'Cuti Melahirkan', 'Cuti Menikah'];
		// $status 									= ['CN', 'CI', 'CI'];
		// $quota 									= ['12', '15', '18', '14', '21', '6'];
		$workleaves 								= ['Cuti Tahunan'];
		$status 									= ['CN'];
		$quota 										= ['12'];
		try
		{
			foreach(range(0, count($organisations)-1) as $org)
			{
				$organisation 						= Organisation::find($organisations[$org]->id);
				foreach(range(0, count($workleaves)-1) as $index)
				{
					$data 							= new Workleave;
					$data->fill([
						'name'						=> $workleaves[$index],
						'quota'						=> $quota[rand(0, count($quota)-1)],
						'status'					=> $status[$index],
						'is_active'					=> true,
					]);

					$data->Organisation()->associate($organisation);

					if (!$data->save())
					{
						print_r($data->getError());
						exit;
					}
				} 
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}