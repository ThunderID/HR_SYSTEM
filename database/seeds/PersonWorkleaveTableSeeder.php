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
		$status 									= ['offer', 'annual', 'special', 'confirmed'];
		$quota 										= [2, 7, 14, 30];
		try
		{
			foreach(range(1, $persons * 2) as $index)
			{
				$rand 								= rand(0, 3);
				$person 							= Person::where('id', ($index%50) + 1)->with(['works' => function($q){$q->wherenull('end');}])->first();

				if(isset($person->works[0]))
				{
					if($status[$rand]=='annual')
					{
						$start 						= date('Y-m-d',strtotime('first day of january 2015'));
						$end 						= date('Y-m-d',strtotime('last day of december 2015'));
					}
					else
					{
						$start 						= date('Y-m-d',strtotime(rand(1,12).'-'.rand(1,28).'-2015'));
						$end 						= date('Y-m-d',strtotime($start.' + '.$quota[$rand].' days'));
					}

					$data 								= new PersonWorkleave;
					$data->fill([
						'created_by'					=> 1,
						'work_id'						=> $person->works[0]->id,
						'name'							=> 'Cuti '.$status[$rand].' 2015',
						'notes'							=> 'Cuti '.$status[$rand].' 2015',
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
				}
			} 
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}