<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workleave;
use App\Models\PersonWorkleave;
use App\Models\Person;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class PersonWorkleaveTableSeeder extends Seeder
{
	function run()
	{

		DB::table('person_workleaves')->truncate();
		$faker 										= Factory::create();
		$persons 									= Person::count();
		$status 									= ['CN', 'CB'];
		$quota 										= [12, 5, 7, 2, 1];
		try
		{
			foreach(range(1, $persons) as $index)
			{
				$rand 								= rand(0, 0);
				$person 							= Person::where('id', ($index%50) + 1)->with(['works' => function($q){$q->wherenull('end')->orwhere('end', '>', date('Y-m-d'));}])->first();

				if(isset($person->works[0]))
				{
					if($status[$rand]=='CN')
					{
						$start 						= date('Y-m-d',strtotime('first day of january 2015'));
						$end 						= date('Y-m-d',strtotime('last day of december 2015'));
					}
					else
					{
						$start 						= date('Y-m-d',strtotime(rand(1,12).'-'.rand(1,28).'-2015'));
						$end 						= date('Y-m-d',strtotime($start.' + '.$quota[$rand].' days'));
					}

					//pemberian cuti tahunan
					$data 								= new PersonWorkleave;
					$data->fill([
						'workleave_id'					=> 1,
						'created_by'					=> 1,
						'work_id'						=> $person->works[0]->id,
						'name'							=> 'Cuti Tahunan 2015',
						'notes'							=> 'Cuti Tahunan 2015',
						'start'							=> $start,
						'end'							=> $end,
						'status'						=> $status[$rand],
						'quota'							=> $quota[$rand],
					]);

					$data->Person()->associate($person);

					if (!$data->save())
					{
						print_r($data->getError());
						exit;
					}


					$start 								= date('Y-m-d',strtotime(rand(1,12).'-'.rand(1,28).'-2015'));
					$end 								= date('Y-m-d',strtotime($start.' + '.$quota[$rand+1].' days'));

					//pengambilan cuti bersama
					$data2 								= new PersonWorkleave;
					$data2->fill([
						'person_workleave_id'			=> $data->id,
						'created_by'					=> 1,
						'work_id'						=> $person->works[0]->id,
						'name'							=> 'Cuti Bersama 2015',
						'notes'							=> 'Cuti Bersama 2015',
						'start'							=> $start,
						'end'							=> $end,
						'status'						=> $status[$rand+1],
						'quota'							=> $quota[$rand+1],
					]);

					$data2->Person()->associate($person);

					if (!$data2->save())
					{
						print_r($data2->getError());
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