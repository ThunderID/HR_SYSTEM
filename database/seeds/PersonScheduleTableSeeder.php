<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\PersonSchedule;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class PersonScheduleTableSeeder extends Seeder
{
	function run()
	{

		DB::table('person_schedules')->truncate();
		$faker 									= Factory::create();
		$total_persons  						= Person::count();
		$status 								= ['HB', 'DN', 'CN', 'L'];
		$schedule 								= ['jam normal', 'dinas luar', 'cuti pribadi', 'libur', 'hari jumat', 'minggu', 'lembur', 'cuti tahunan'];
		$start 									= ['08:00:00', '13:00:00', '00:00:00', '00:00:00', '08:00:00', '08:00:00', '20:00:00', '00:00:00'];
		$end 									= ['16:00:00', '16:00:00', '00:00:00', '00:00:00', '15:00:00', '10:00:00', '00:00:00', '00:00:00'];
		try
		{
			foreach(range(1, 20) as $index)
			{
				$rand 							= rand(0,3);
				$randay 						= rand(2,40);
				$data 							= new PersonSchedule;
				$data->fill([
					'created_by'				=> 1,
					'on'						=> date('Y-m-d', strtotime('+ '.$randay.' days')),
					'name'						=> $schedule[$rand],
					'status'					=> $status[$rand],
					'start'						=> $start[$rand],
					'end'						=> $end[$rand],
				]);

				$person 						= Person::find(rand($randay+1, $total_persons));

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