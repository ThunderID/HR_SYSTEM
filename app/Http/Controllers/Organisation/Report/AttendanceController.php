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
use App\Models\Work;
use App\Models\Branch;
use App\Models\ProcessLog;

class AttendanceController extends BaseController 
{
	protected $controller_name 						= 'attendance';

	public function index()
	{		
		// ---------------------- LOAD DATA ----------------------
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		if(Input::has('start'))
		{
			$start 									= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 									= date('Y-m-d', strtotime('first day of january '.date('Y')));
		}

		if(Input::has('end'))
		{
			$end 									= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 									= date('Y-m-d', strtotime('last day of december '.date('Y')));
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
		if(Session::get('user.menuid')>=5)
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
		
		
		if(Session::get('user.menuid')>=5)
		{
			$filter['search']['chartchild'] 		= Session::get('user.chartpath');
			$filter['active']['chartchild'] 		= 'Lihat Sebagai "'.Session::get('user.chartname').'"';
		}

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

		$this->layout->page 						= view('pages.organisation.report.attendance.index', compact('data', 'start', 'end'));
		$this->layout->page->filter 				= $filters;
		$this->layout->page->filtered 				= $filter;
		$this->layout->page->default_filter  		= ['org_id' => $data['id'], 'start' => $start, 'end' => $end];

		if(Input::has('print'))
		{
			$search 								= ['globalattendance' => array_merge(['organisationid' => $data['id'], 'on' => [$start, $end]], (isset($filtered['search']) ? $filtered['search'] : []))];
			$sort 									= (isset($filtered['sort']) ? $filtered['sort'] : ['persons.name' => 'asc']);
			$page 									= 1;
			$per_page 								= 100;
			$search['organisationid'] 				= ['fieldname' => 'persons.organisation_id', 'variable' => $data['id']];
			$results 								= $this->dispatch(new Getting(new Person, $search, $sort , (int)$page, (int)$per_page, isset($new) ? $new : false));

			$contents 								= json_decode($results);

			if(!$contents->meta->success)
			{	
				App::abort(404);	
			}
			$report 								= json_decode(json_encode($contents->data), true);

			Excel::create('Laporan Kehadiran per tanggal ( '.$start.' s.d '.$end.' ) '.$data['name'], function($excel) use ($report, $start, $end, $data) 
			{
				// Set the title
				$excel->setTitle('Laporan Kehadiran');
				// Call them separately
				$excel->setDescription('Laporan Kehadiran');
				$excel->sheet('Sheetname', function ($sheet) use ($report, $start, $end, $data) 
				{
					$c 									= count($report);
					$sheet->loadView('widgets.organisation.report.attendance.table_csv')->with('data', $report)->with('start', $start)->with('end', $end)->with('org', $data);
				});
			})->export(Input::get('mode'));
		}

		return $this->layout;
	}

	public function show($person_id = null)
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
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

		$search['id']								= $person_id;
		$search['organisationid']					= $org_id;
		$search['withattributes']					= ['organisation'];
		$sort 										= ['name' => 'asc'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];

		$this->layout->page 						= view('pages.organisation.report.attendance.show', compact('data', 'start', 'end', 'person'));

		if(Input::has('print'))
		{
			$search 								= ['id' => $person['id'], 'processlogsondate' => ['on' => [$start, $end]]];
			$sort 									= ['persons.name' => 'asc'];
			$page 									= 1;
			$per_page 								= 1;
			$search['organisationid'] 				= ['fieldname' => 'persons.organisation_id', 'variable' => $data['id']];
			$results 								= $this->dispatch(new Getting(new Person, $search, $sort , (int)$page, (int)$per_page, isset($new) ? $new : false));

			$contents 									= json_decode($results);

			if(!$contents->meta->success)
			{	
				App::abort(404);	
			}
			$report 								= json_decode(json_encode($contents->data), true);
			

			Excel::create('Laporan Kehadiran '.$report['name'].' per tanggal ( '.$start.' s.d '.$end.' )', function($excel) use ($report, $start, $end, $data) 
			{
				// Set the title
				$excel->setTitle('Laporan Kehadiran');
				// Call them separately
				$excel->setDescription('Laporan Kehadiran Perorangan');
				$excel->sheet('Sheetname', function ($sheet) use ($report, $start, $end, $data) 
				{
					$c 									= count($report);
					$sheet->loadView('widgets.organisation.report.attendance.person.table_csv')->with('data', $report)->with('start', $start)->with('end', $end)->with('org', $data);
				});
			})->export(Input::get('mode'));	
		}

		return $this->layout;
	}


	public function create($id = null)
	{
		$errors 								= new MessageBag();

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id']							= $person_id;
		$search['organisationid']				= $org_id;
		$search['withattributes']				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		if(!is_null($id))
		{
			if(!Input::has('ondate'))
			{
				App::abort(404);
			}

			if((int)Session::Get('user.menuid')==2)
			{
				$dateline 						= date('Y-m-d', strtotime(Input::get('ondate'). ' + 2 months'));

				if($dateline < date('Y-m-d'))
				{
					$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 2 Bulan');
					
					return Redirect::back()->withErrors($errors)->withInput();
				}
			}

			if((int)Session::Get('user.menuid')==3)
			{
				$dateline 						= date('Y-m-d', strtotime(Input::get('ondate'). ' + 7 days'));

				if($dateline < date('Y-m-d'))
				{
					$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 7 hari');
					
					return Redirect::back()->withErrors($errors)->withInput();
				}
			}

			$ondate 							= date('Y-m-d', strtotime(Input::get('ondate')));
			$start 								= date('Y-m-d', strtotime(Input::get('start')));
			$end 								= date('Y-m-d', strtotime(Input::get('end')));

		}

		$this->layout->page 					= view('pages.organisation.report.attendance.create', compact('id', 'data', 'person', 'ondate', 'start', 'end'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 				= Session::get('user.chartpath');
		}
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('modified_status');
		$attributes['modified_by']				= Session::get('loggedUser');
		$attributes['modified_at']				= date('Y-m-d H:i:s');

		$errors 								= new MessageBag();

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new ProcessLog, $attributes, $id, new Person, $person_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('ProcessLog', $value2);
					}
				}
				else
				{
					$errors->add('ProcessLog', $value);
				}
			}
		}

		$start 									= date('Y-m-d', strtotime(Input::get('start')));
		$end 									= date('Y-m-d', strtotime(Input::get('end')));

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.report.attendances.show', array_merge(['person_id' => $person_id, 'org_id' => $org_id], ['start' => $start, 'end' => $end]))->with('alert_success', 'Status kehadiran karyawan "' . $contents->data->name. '" sudah disimpan');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function edit($id)
	{
		return $this->create($id);
	}

}
