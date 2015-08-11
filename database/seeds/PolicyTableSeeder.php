<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organisation;
use App\Models\Policy;

class PolicyTableSeeder extends Seeder
{
	function run()
	{
		DB::table('tmp_policies')->truncate();

		$org_totals 								= Organisation::count();
		$types 										= ['passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle'];
		$values 									= ['+ 3 months', '1', '1', '2', '2', '2', '+ 31 days', '+ 5 days', '900', '3600','7200'];
		try
		{
			foreach(range(0, count($org_totals)-1) as $index)
			{
				foreach(range(0, count($types)-1) as $key2 => $index2)
				{
					$data 								= new Policy;
					$data->fill([
						'updated_by'					=> 1,
						'type'							=> $types[$key2],
						'value'							=> $values[$key2],
					]);

					$data->organisation()->associate(Organisation::find($index+1));

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