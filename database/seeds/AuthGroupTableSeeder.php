<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\AuthGroup;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class AuthGroupTableSeeder extends Seeder
{
	function run()
	{
		DB::table('tmp_auth_groups')->truncate();
		$level 										= ['SYSTEM LEVEL I. SUPERADMIN', 'HR LEVEL 1. HEAD OF CORPORATE', 'HR LEVEL 2. HR OPERATION MANAGER', 'HR LEVEL 3. HR ADMINISTRATION', 'GLOBAL LEVEL 4. RECRUIT TEAM'];
		$level1 									= [];
		$level2 									= [];
		$level3 									= [];
		$level4 									= [];
		$level5 									= [];
		foreach(range(1, 101) as $index)
		{
			$level1[] 								= $index;
			if($index==1 || ($index>=5 && $index<=16) || ($index>=25 && $index<=48) || ($index>=61 && $index<=100))
			{
				$level2[] 							= $index;
				if($index%4!=0)
				{
					$level3[]						= $index;
					if($index%4!=3)
					{
						$level4[]					= $index;
						if($index%4!=2)
						{
							$level5[]				= $index;
						}
					}
				}
			}			
		}

		try
		{
			foreach(range(0, count($level)-1) as $index)
			{
				$data 								= new AuthGroup;
				$data->fill([
					'name'							=> $level[$index],
				]);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
				switch($index)
				{
					case 0 :
						$data->menus()->sync($level1);
					break;
					case 1 :
						$data->menus()->sync($level2);
					break;
					case 2 :
						$data->menus()->sync($level3);
					break;
					case 3 :
						$data->menus()->sync($level4);
					break;
					default:
						$data->menus()->sync($level5);
					break;
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	
	}
}