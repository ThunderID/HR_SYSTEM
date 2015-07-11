<?php namespace App\Http\Controllers\Organisation\Report;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image, Excel;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Branch;

class AttendanceController extends BaseController 
{
	protected $controller_name 						= 'attendance';

	public function index()
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		if(Input::has('start'))
		{
			$start 									= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 									= date('Y-m-d', strtotime('first day of this month'));
		}

		if(Input::has('end'))
		{
			$end 									= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 									= date('Y-m-d', strtotime('last day of next month'));
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']								= $org_id;
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);


		unset($search);
		unset($sort);

		$search 									= ['organisationid' => $org_id];
		if(Session::get('user.menuid')>=4)
		{
			$search['id'] 							= Session::get('user.branchid');
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		else
		{
			$search['withattributes'] 				= ['charts'];
		}
		$results 									= $this->dispatch(new Getting(new Branch, $search, [] , 1, 100));
		$contents 									= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$branch 									= json_decode(json_encode($contents->data), true);

		$tags 										= [];

		foreach ($branch as $key => $value) 
		{
			$branches[$key]['key'] 					= $value['id'];
			$branches[$key]['value'] 				= $value['name'];
			if(Input::has('key'))
			{
				foreach (Input::get('key') as $key2 => $value2) 
				{
					if(strtolower($value2)=='search_branchid' && (int)Input::get('value')[$key2]==(int)$value['id'])
					{
						$branchname 				= $value['name'];
					}
				}
			}
			if(isset($filter['search']['branchid']))
			{
				foreach ($value['charts'] as $key2 => $value2) 
				{
					if(!in_array($value2['tag'], $tags))
					{
						$charts[$key]['key'] 		= $value2['tag'];
						$charts[$key]['value'] 		= $value2['tag'];
						$tags[]						= $value2['tag'];
					}
				}
			}
		}

		$filter 									= [];
		if(Input::has('q'))
		{
			$filter['search']['name']				= Input::get('q');
			$filter['active']['q']					= 'Cari Nama "'.Input::get('q').'"';
		}
		if(Input::has('filter'))
		{
			$dirty_filter 							= Input::get('key');
			$dirty_filter_value 					= Input::get('value');

			foreach ($dirty_filter as $key => $value) 
			{
				if (str_is('search_*', strtolower($value)))
				{
					$filter_search 						= str_replace('search_', '', $value);
					$filter['search'][$filter_search]	= $dirty_filter_value[$key];
					$filter['active'][$filter_search]	= $dirty_filter_value[$key];
					switch (strtolower($filter_search)) 
					{
						case 'name':
							$active = 'Cari Nama ';
							break;

						case 'branchid':
							$active = 'Cari Cabang ';
							break;

						case 'charttag':
							$active = 'Cari Departemen ';
							break;

						default:
							$active = 'Cari Nama ';
							break;
					}

					switch (strtolower($filter_search)) 
					{
						//need to enhance to get branch name
						case 'branchid':
							if(isset($branchname))
							{
								$active = $active.'"'.$branchname.'"';
							}
							else
							{
								$active = $active.'"'.$dirty_filter_value[$key].'"';
							}
							break;
						
						default:
							$active = $active.'"'.$dirty_filter_value[$key].'"';
							break;
					}

					$filter['active'][$filter_search]	= $active;

				}
				if (str_is('sort_*', strtolower($value)))
				{
					$filter_sort 						= str_replace('sort_', '', $value);
					$filter['sort'][$filter_sort]		= $dirty_filter_value[$key];
					switch (strtolower($filter_sort)) 
					{
						case 'name':
							$active = 'Urutkan Nama';
							break;
						
						default:
							$active = 'Urutkan Nama';
							break;
					}

					switch (strtolower($dirty_filter_value[$key])) 
					{
						case 'asc':
							$active = $active.' (Z-A)';
							break;
						
						default:
							$active = $active.' (A-Z)';
							break;
					}

					$filter['active'][$filter_sort]		= $active;
				}
			}
		}
		
		
		if(Session::get('user.menuid')>=4)
		{
			$filter['search']['chartchild'] 		= Session::get('user.chartpath');
			$filter['active']['chartchild'] 		= 'Lihat Sebagai "'.Session::get('user.chartname').'"';
		}
		
		$this->layout->page 						= view('pages.organisation.report.attendance.index', compact('data', 'start', 'end'));

		$filters 									= 	[
															['prefix' => 'sort', 'key' => 'persons.name', 'value' => 'Urutkan Nama', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
														];

		if(isset($branches))
		{
			$filters[] 					 			= ['prefix' => 'search', 'key' => 'branchid', 'value' => 'Cari Di Cabang', 'values' => $branches];
		}
		if(isset($charts))
		{
			$filters[] 					 			= ['prefix' => 'search', 'key' => 'charttag', 'value' => 'Cari Di Departemen', 'values' => $charts];
		}

		$this->layout->page->filter 				= $filters;
		$this->layout->page->filtered 				= $filter;
		$this->layout->page->default_filter  		= ['org_id' => $data['id'], 'start' => $start, 'end' => $end];

		if(Input::has('print'))
		{
			$search 								= ['globalattendance' => array_merge(['organisationid' => $data['id'], 'on' => [$start, $end]], (isset($filter['search']) ? $filter['search'] : []))];
			$sort 									= (isset($filtered['sort']) ? $filtered['sort'] : ['persons.name' => 'asc']);
			$page 									= 1;
			$per_page 								= 100;
			$search['organisationid'] 				= ['fieldname' => 'persons.organisation_id', 'variable' => $data['id']];
			$results 									= $this->dispatch(new Getting(new Person, $search, $sort , (int)$page, (int)$per_page, isset($new) ? $new : false));

			$contents 									= json_decode($results);

			if(!$contents->meta->success)
			{	
				App::abort(404);	
			}
			$report 								= json_decode(json_encode($contents->data), true);

			// $case = Input::get('case');
			Excel::create('Laporan Aktivitas per tanggal ( '.$start.' s.d '.$end.' ) di '.$data['name'], function($excel) use ($report, $start, $end) 
			{
				// Set the title
				$excel->setTitle('Laporan Aktivitas');
				// Call them separately
				$excel->setDescription('Laporan Aktivitas');
				$excel->sheet('Sheetname', function ($sheet) use ($report, $start, $end) 
				{
					$c 									= count($report);
					$sheet->loadView('widgets.organisation.report.attendance.table_csv')->with('data', $report)->with('start', $start)->with('end', $end);
				});
			})->export(Input::get('mode'));
			// dD($report
			///nama viewnya			
		}

		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		$this->layout->page 					= view('pages.person.create', compact('id', 'data'));

		return $this->layout;
	}
}
