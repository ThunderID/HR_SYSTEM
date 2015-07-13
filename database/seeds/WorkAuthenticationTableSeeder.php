<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;
use App\Models\WorkAuthentication;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class WorkAuthenticationTableSeeder extends Seeder
{
	function run()
	{
		$works 										= Work::where('status', 'admin')->get();
		$app 										= ['web', 'tracker', 'fingerprint'];
		try
		{
			foreach(range(0, count($works)-1) as $index)
			{
				$data 								= new WorkAuthentication;
				$data->fill([
					'tmp_auth_group_id'				=> 1,
					'organisation_id'				=> $works[$index]->chart->branch->organisation_id,
				]);

				$data->Work()->associate($works[$index]);

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