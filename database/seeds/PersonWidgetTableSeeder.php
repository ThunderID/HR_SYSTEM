<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Person;
use App\Models\PersonWidget;

class PersonWidgetTableSeeder extends Seeder
{
	function run()
	{
		DB::table('person_widgets')->truncate();

		$person_totals 								= Person::count();
		$menus 										= [
														'total surat peringatan',
														'total kontrak kerja' , 
														'total cabang', 
														'total pegawai', 
														'pegawai baru 3 hari terakhir', 
														'pegawai berhenti 3 hari terakhir', 
														'surat peringatan 3 hari terakhir',
														'cabang dengan pegawai berhenti terbanyak', 
														];
		$function 									= [
														'total_documents',
														'total_documents', 
														'total_branches', 
														'total_employees', 
														'index_employees', 
														'index_employees', 
														'index_documents', 
														'index_branches', 
														];
		$type 										= ['stat','stat' , 'stat', 'stat', 'panel', 'panel', 'table', 'table'];
		$query 										= [
														json_encode(['tag' => 'SP', 'organisationID' => 1]), 
														json_encode(['tag' => 'Kontrak', 'organisationID' => 1]), 
														json_encode(['organisationID' => 1]), 
														json_encode(['checkwork' => 'active']), 
														json_encode(['checkwork' => '- 3 days']), 
														json_encode(['checkResign' => '- 3 days']), 
														json_encode(['tag' => 'SP', 'organisationID' => 1, 'checkreceiver' => '- 3 days']), 
														json_encode(['organisationID' => 1, 'countResign' => 'inactive']), 
													];
		$field 										= [
														'', 
														'', 
														'', 
														'', 
														'', 
														'', 
														json_encode(['name', 'created_at']), 
														json_encode(['name', 'count'])
													];
		$row 										= [1,1,1,1,2,2,3,3];
		$col 										= [1,2,3,4,1,2,1,2];

		try
		{
			foreach(range(0, count($menus)-1) as $index)
			{
				$data 								= new PersonWidget;
				$data->fill([
					'title'							=>str_replace('_', ' ', $menus[$index]),
					'type'							=>$type[$index],
					'row'							=>$row[$index],
					'col'							=>$col[$index],
					'query'							=>$query[$index],
					'field'							=>$field[$index],
					'function'						=>$function[$index],
				]);

				$data->person()->associate(Person::find(1));

				if (!$data->save())
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