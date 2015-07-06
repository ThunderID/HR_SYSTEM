<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organisation;
use App\Models\SettingIdle;

class SettingIdleTableSeeder extends Seeder
{
	function run()
	{
		DB::table('setting_idles')->truncate();

		$org_totals 								= Organisation::count();
		try
		{
			foreach(range(0, count($org_totals)-1) as $index)
			{
				$data 								= new SettingIdle;
				$data->fill([
					'created_by'					=> 1,
					'start'							=> date('Y-m-d', strtotime('now')),
					'idle_1'						=> 900,
					'idle_2'						=> 3600,
				]);

				$data->organisation()->associate(Organisation::find($index+1));

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