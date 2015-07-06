<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calendar;
use App\Models\Schedule;
use \Faker\Factory, Illuminate\Support\Facades\DB;
use \DateTime, \DateInterval, \DatePeriod;

class ScheduleTableSeeder extends Seeder
{
	function run()
	{

		DB::table('tmp_schedules')->truncate();
		$faker 										= Factory::create();
		$total_cals  								= Calendar::count();
		$schedule 									= ['shift pagi', 'shift malam', 'jam normal', 'hari sabtu', 'hari jumat', 'minggu', 'lembur'];
		$start 										= ['08:00:00', '16:00:00', '08:00:00', '08:00:00', '08:00:00', '08:00:00', '20:00:00'];
		$end 										= ['16:00:00', '00:00:00', '16:00:00', '12:00:00', '15:00:00', '10:00:00', '00:00:00'];
		$status 									= ['presence_indoor', 'presence_outdoor', 'absence_workleave', 'absence_not_workleave'];
		try
		{
			foreach(range(1, $total_cals) as $index)
			{
				$rand 								= rand(0,3);
				$begin 								= new DateTime( 'first day of july 2015' );
				$ended 								= new DateTime( 'last day of july 2015'  );

				$interval 							= DateInterval::createFromDateString('1 day');
				$periods 							= new DatePeriod($begin, $interval, $ended);

				foreach ( $periods as $period )
				{
					$data 							= new Schedule;
					$data->fill([
						'created_by'				=> 1,
						'on'						=> $period->format('Y-m-d'),
						'status'					=> $status[$rand],
						'name'						=> str_replace('_', ' ', $status[$rand]),
						'start'						=> $start[$rand],
						'end'						=> $end[$rand],
					]);

					$calendar 						= Calendar::find($index);

					$data->Calendar()->associate($calendar);

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