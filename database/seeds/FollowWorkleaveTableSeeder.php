<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workleave;
use App\Models\Organisation;
use App\Models\Work;
use App\Models\FollowWorkleave;
use \Faker\Factory, Illuminate\Support\Facades\DB;

class FollowWorkleaveTableSeeder extends Seeder
{
	function run()
	{

		DB::table('follow_workleaves')->truncate();
		$organisations 								= Organisation::get();
		try
		{
			foreach(range(0, count($organisations)-1) as $org)
			{
				$org_id 							= $organisations[$org]->id;

				$workleave 							= Workleave::where('organisation_id', $org_id)->where('status', 'CN')->where('is_active', true)->orderby('created_at')->first();
				$works 								= Work::wheredoesnthave('followworkleave', function($q){$q;})->wherehas('chart.branch.organisation', function($q)use($org_id){$q->where('id', $org_id);})->get();
				foreach(range(0, count($works)-1) as $index)
				{
					$data 							= new FollowWorkleave;
					$data->fill([
						'work_id'					=> $works[$index]->id,
					]);

					$data->Workleave()->associate($workleave);

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