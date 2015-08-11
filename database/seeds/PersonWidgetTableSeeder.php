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
		$widgets 									= [
														'widgets.organisation.person.stat.total_employee',
														'widgets.organisation.branch.stat.total_branch', 
														'widgets.organisation.document.stat.total_document', 
														'widgets.organisation.person.stat.average_loss_rate', 
														'widgets.organisation.person.table', 
														];
		$type 										= ['stat', 'stat' , 'stat', 'stat', 'table', 'list', 'table', 'list'];
		$query 										= [
														json_encode([
															'widget_template'		=> 'plain',
															'widget_title'			=> 'Total Karyawan ',
															'widget_options'		=> 	[
																							'personlist'		=>
																							[
																								'title'				=> 'Total Karyawan',
																								'organisation_id'	=> 1,
																								'search'			=> ['chartnotadmin' => true],
																								'sort'				=> [],
																								'page'				=> 1,
																								'per_page'			=> 100,
																							]
																						]
														]), 
														json_encode([
															'widget_template'		=> 'plain',
															'widget_title'			=> 'Total Cabang ',
															'widget_options'		=> 	[
																							'branchlist'		=>
																							[
																								'organisation_id'	=> 1,
																								'search'			=> [],
																								'sort'				=> [],
																								'page'				=> 1,
																								'per_page'			=> 100,
																							]
																						]
														]), 
														json_encode([
															'widget_template'		=> 'plain',
															'widget_title'			=> 'Total Dokumen ',
															'widget_options'		=> 	[
																							'documentlist'		=>
																							[
																								'organisation_id'	=> 1,
																								'search'			=> [],
																								'sort'				=> [],
																								'page'				=> 1,
																								'per_page'			=> 100,
																							]
																						]
														]), 
														json_encode([
															'widget_template'		=> 'plain',
															'widget_title'			=> 'Average Loss Rate ',
															'widget_options'		=> 	[
																							'lossratelist'		=>
																							[
																								'organisation_id'	=> 1,
																								'search'			=> ['globalattendance' => ['organisationid' => 1, 'on' => [date('Y-m-d', strtotime('first day of this month')), date('Y-m-d', strtotime('first day of next month'))]]],
																								'sort'				=> [],
																								'page'				=> 1,
																								'per_page'			=> 40,
																							]
																						]
														]), 
														json_encode([
															'widget_template'		=> 'panel',
															'widget_title'			=> '<h4>Absen Karyawan "'.'" ('.date('d-m-Y', strtotime('- 1 day')).')</h4>',
															'widget_options'		=> 	[
																							'personlist'		=>
																							[
																								'organisation_id'	=> 1,
																								'search'			=> ['fullschedule' => date('Y-m-d', strtotime('- 1 day')), 'withattributes' => ['works.branch']],
																								'sort'				=> [],
																								'page'				=> 1,
																								'per_page'			=> 40,
																								'route_create'		=> route('hr.persons.create', ['org_id' => 1])
																							]
																						]
														]), 
													];
		$row 										= [1,1,1,1,2,2,3,3];
		$col 										= [1,2,3,4,1,2,1,2];

		try
		{
			foreach(range(0, count($widgets)-1) as $index)
			{
				$data 								= new PersonWidget;
				$data->fill([
					'type'							=>$type[$index],
					'widget'						=>$widgets[$index],
					'query'							=>$query[$index],
					'dashboard'						=>'organisation',
					'row'							=>$row[$index],
					'col'							=>$col[$index],
					'is_active'						=>true,
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