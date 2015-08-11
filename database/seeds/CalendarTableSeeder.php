<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calendar;
use App\Models\Person;
use App\Models\Organisation;
use App\Models\Branch;
use App\Models\Chart;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class CalendarTableSeeder extends Seeder
{
	function run()
	{

		DB::table('tmp_calendars')->truncate();
		$faker 										= Factory::create();
		$total_persons  							= Person::count();
		$total_orgs 		 						= Organisation::count();
		$total_positions 	 						= Chart::count();
		$total_branches 	 						= Branch::get();
		$calendar 									= ['wib', 'wita', 'wit'];
		try
		{
			foreach(range(0, count($total_orgs)-1) as $index)
			{
				
				$start 								= '08:00:00';//date('H:i:s', strtotime('+ '.rand(2,5).' hours'.' + '.rand(2,59).' minutes'.' + '.rand(2,59).' seconds'));
				$data 								= new Calendar;
				$data->fill([
					'organisation_id'				=> rand(1, $total_orgs),
					'name'							=> $faker->country,
					'workdays'						=> 'senin,selasa,rabu,kamis,jumat',
					'start'							=> $start,
					'end'							=> date('H:i:s', strtotime($start.' + 9 hours')),
				]);


				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
					
				foreach(range(0, count($total_branches)-1) as $index2)
				{
					foreach(range(0, count($total_branches[$index2]->charts)-1) as $index3)
					{
						$chart 								= $total_branches[$index2]->charts[$index3]->id;
						$data->Charts()->attach($chart);

						if (!$data->save())
						{
							print_r($data->getError());
							exit;
						}
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