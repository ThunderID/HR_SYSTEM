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

		$orgs 										= Organisation::get();
		$types 										= ['passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle', 'extendsworkleave', 'extendsmidworkleave', 'firstacleditor', 'secondacleditor'];
		$values 									= ['- 3 months', '1', '1', '2', '2', '2', '- 1 month', '- 5 days', '900', '3600','7200', '+ 3 months', '+ 15 months', '- 1 month', '- 5 days'];
		try
		{
			foreach(range(0, count($orgs)-1) as $index)
			{
				foreach(range(0, count($types)-1) as $key2 => $index2)
				{
					$data 								= new Policy;
					$data->fill([
						'created_by'					=> 1,
						'type'							=> $types[$key2],
						'value'							=> $values[$key2],
						'started_at'					=> date('Y-m-d'),
					]);

					$data->organisation()->associate(Organisation::find($orgs[$index]->id));

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