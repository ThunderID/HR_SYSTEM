<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organisation;
use App\Models\Branch;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class BranchTableSeeder extends Seeder
{
	function run()
	{
		DB::table('branches')->truncate();
		$branch 										= 	[
																'Aceh',
																'Padang',
																'Jakarta Pusat',
																'Jakarta Barat',
																'Jogjakarta',
																'Solo',
																'Klaten',
																'Jember',
																'Malang',
																'Malang',
																'Ternate',
																'Tidore',
																'Bali',
																'Kupang',
																'Bangka',
																'Papua',
															];
		$faker 											= Factory::create();
		$organisation_total 							= Organisation::count();
		try
		{
			foreach(range(1, $organisation_total) as $index)
			{
				$organisation 							= Organisation::find($index);
				foreach(range(1, rand(1,15)) as $index2)
				{
					$data 								= new Branch;
					$data->fill([
						'name'							=> $branch[rand(1,15)],
					]);

					if (!$data->save())
					{
						print_r($data->getError());
						exit;
					}

					$data->organisation()->associate($organisation);
					$data->save();
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}