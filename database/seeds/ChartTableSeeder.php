<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Branch;
use App\Models\Chart;
use \Faker\Factory;
use Illuminate\Support\Facades\DB;

class ChartTableSeeder extends Seeder
{
	function run()
	{
		DB::table('charts')->truncate();
		$faker 												= Factory::create();
		$org_branch_total 									= Branch::count();
		$position 											= ['CEO', 'Manager', 'Staff'];
		$department 										= ['General', 'HR', 'CRM', 'Accounting'];
		$pos 												= ['1', '5', '4', '3'];
		$max 												= ['1', '12', '10', '6'];
		try
		{
			$i 											= 1;
			foreach(range(1, $org_branch_total) as $index)
			{
				$org_branch 							= Branch::find($index);
				foreach(range(1, count($department)) as $index2)
				{
					foreach(range(1, count($pos)) as $index3)
					{
						if($index2==1 &&$index3==1)
						{
							$posin 								= 0;
							$root 								= $i;
						}
						elseif($index3==1)
						{
							$posin 								= 1;
							$chart_id							= $root;
						}
						else
						{
							$posin 								= 2;
							$chart_id							= $root;
						}
						if($index2==1 &&$index3==1)
						{							
							$data 								= new Chart;
							$data->fill([
								'path'							=> $root,
								'name'							=> $position[$posin],
								'tag'							=> $department[$index2-1],
								'min_employee'					=> 1,
								'ideal_employee'				=> $pos[$index2-1],
								'max_employee'					=> $max[$index2-1],
								'current_employee'				=> 1
							]);
						}
						else
						{
							$data 								= new Chart;
							$data->fill([
								'chart_id'						=> $root,
								'path'							=> $root,
								'name'							=> $position[$posin],
								'tag'							=> $department[$index2-1],
								'min_employee'					=> 1,
								'ideal_employee'				=> $pos[$index2-1],
								'max_employee'					=> $max[$index2-1],
								'current_employee'				=> 1
							]);
						}
						if (!$data->save())
						{
							print_r($data->getError());
							exit;
						}
						$data->branch()->associate($org_branch);
						$data->save();
						$i++;
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