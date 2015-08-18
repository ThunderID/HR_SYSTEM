<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Log;
use App\Models\Person;
use \Faker\Factory, Illuminate\Support\Facades\DB;
use \DateTime, \DateInterval, \DatePeriod;

class LogTableSeeder extends Seeder
{
	function run()
	{

		DB::table('logs')->truncate();
		DB::table('process_logs')->truncate();
		DB::table('attendance_logs')->truncate();
		DB::table('idle_logs')->truncate();
		DB::table('attendance_details')->truncate();
		$faker 										= Factory::create();
		$total_persons  							= Person::activeworks(true)->count();
		$logs 										= ['login', 'logout','presence', 'idle', 'lock', 'working', 'presence', 'sleep', 'presence'];
		$pcs 										= ['redhat', 'ubuntu', 'debian', 'mint', 'centos', '7', 'xp', 'fp', 'fp', 'fp'];
		try
		{
			foreach(range(1, $total_persons) as $index)
			{
				$person 							= Person::find($index);
				$rand 								= rand(0,2);
				$begin 								= new DateTime( 'first day of june 2015' );
				$ended 								= new DateTime( 'last day of june 2015'  );

				$interval 							= DateInterval::createFromDateString('1 day');
				$periods 							= new DatePeriod($begin, $interval, $ended);

				foreach ( $periods as $period )
				{
					foreach(range(8, 16) as $index2)
					{
						if($index2==1)
						{
							$state 					= 0;
							$hour 					= $index2.' hour';
							$minute 				= rand(2,60).' minutes';
							$second 				= rand(2,60).' seconds';
						}
						elseif($index2==8)
						{
							$state					= rand(1,3);
							$hour 					= $index2.' hours';
							$minute 				= rand(2,60).' minutes';
							$second 				= rand(2,60).' seconds';
						}
						else
						{
							$state 					= rand(2,8);
							$hour 					= $index2.' hours';
							$minute 				= rand(2,60).' minutes';
							$second 				= rand(2,60).' seconds';
						}

						$rand 						= rand(0,8);
						$data 						= new Log;
						$data->fill([
							'name'					=> $logs[$state],
							'on'					=> date("Y-m-d H:i:s", strtotime($period->format('Y-m-d').' + '.$hour.' + '.$minute.' + '.$second)),
							'last_input_time'		=> date("Y-m-d H:i:s", strtotime($period->format('Y-m-d').' + '.$hour.' + '.$minute.' + '.$second)),
							'pc'					=> $pcs[$rand],
							'app_version'			=> '1.0',
							'ip'					=> getenv("REMOTE_ADDR")
						]);

						$data->Person()->associate($person);

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