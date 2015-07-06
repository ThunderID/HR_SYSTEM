<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\Api;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiTableSeeder extends Seeder
{
	function run()
	{
		DB::table('apis')->truncate();
		$total_branch 								= Branch::count();
		try
		{
			foreach(range(1, $total_branch) as $index)
			{
				$data 								= new Api;
				$data->fill([
					'client'						=> '123456789-'.$index,
					'secret'						=> '123456789',
					'macaddress'					=> 'MACA-'.$index,
					'pc_name'						=> 'CAB-'.$index,
					'tr_version'					=> 'V1.0',
				]);

				$branch 							= Branch::find($index);
				
				$data->branch()->associate($branch);

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