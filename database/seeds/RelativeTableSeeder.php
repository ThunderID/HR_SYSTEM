<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use \DB;

class RelativeTableSeeder extends Seeder
{
	function run()
	{
		DB::table('relatives')->truncate();
		$total_person 								= Person::count();
		try
		{
			$relationship 							= ['spouse', 'parent', 'child', 'partner'];

			foreach(range(1, $total_person) as $index)
			{
				$data 								= Person::find($index);

				$relative 							= Person::find(rand(1,$total_person));

				if (!$data->relatives()->save($relative, ['relationship' => $relationship[rand(0,3)]]))
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