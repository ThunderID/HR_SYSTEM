<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\Application;
use App\Models\Menu;
use App\Models\Chart;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class MenuTableSeeder extends Seeder
{
	function run()
	{
		DB::table('tmp_menus')->truncate();
		DB::table('authentications')->truncate();
		$menus 										= 
														[
															'Manage Organisasi',
															'Manage HR Config',
															'Manage HR Data' , 
															'Manage Team', 
														];

		$trackermenu 								= 
														[
															'Setting'
														];
		$fpmenu 									= 
														[
															'Setting'
														];
		try
		{
			foreach(range(0, count($menus)-1) as $index)
			{
				$data 								= new Menu;
				$data->fill([
					'name'							=> $menus[$index],
				]);

				$app 								= Application::find(1);
				
				$data->application()->associate($app);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}

			foreach(range(0, count($trackermenu)-1) as $index)
			{
				$data 								= new Menu;
				$data->fill([
					'name'							=> $trackermenu[$index],
				]);

				$app 								= Application::find(2);
				
				$data->application()->associate($app);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}

			foreach(range(0, count($fpmenu)-1) as $index)
			{
				$data 								= new Menu;
				$data->fill([
					'name'							=> $fpmenu[$index],
				]);

				$app 								= Application::find(3);
				
				$data->application()->associate($app);

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