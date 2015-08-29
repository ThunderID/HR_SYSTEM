<?php namespace App\Http\Controllers\Organisation\Person;
use Input, Session, App, Paginator, Redirect, DB, Config, DatePeriod, DateTime, DateInterval, Response;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Person;
use App\Models\Workleave;
use App\Models\PersonWorkleave;
use App\Models\Queue;
use App\Models\Work;

class WorkleaveController extends BaseController
{
	protected $controller_name = 'cuti';

	public function index($page = 1)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$search['checkwork'] 					= true;
		$search['currentwork'] 					= null;
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


		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		unset($search);
		unset($sort);

		$search['id'] 							= $person['works'][0]['id'];
		$search['currentworkleave'] 			= true;
		$sort 									= ['created_at' => 'desc'];

		$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			$wleave['id'] 						= 0;
			$wleave['quota'] 					= 0;
			$wleave['work_id'] 					= 0;
			$wleave['follow_workleave_id'] 		= 0;
		}
		else
		{
			$wleave 							= json_decode(json_encode($contents->data->followworkleave->workleave), true);
			$wleave['work_id'] 					= $contents->data->followworkleave->work_id;
			$wleave['follow_workleave_id'] 		= $contents->data->followworkleave->id;
		}

		$filter 									= [];
		if(Input::has('q'))
		{
			$name									= Input::get('q');
			$filter['search']['name']				= $name;
			$filter['active']['q']					= 'Cari Cuti "'.Input::get('q').'"';
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
							$active = 'Cari Cuti ';
							break;

						default:
							$active = 'Cari Cuti ';
							break;
					}

					switch (strtolower($filter_search)) 
					{
						//need to enhance to get branch name
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
						case 'start':
							$active = 'Urutkan Tanggal Mulai Berlaku Cuti';
							break;
						
						case 'end':
							$active = 'Urutkan Tanggal Kadaluarsa Cuti';
							break;

						default:
							$active = 'Urutkan Tanggal';
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
		
		$this->layout->page 						= view('pages.organisation.person.workleave.index');
		$this->layout->page->controller_name 		= $this->controller_name;
		$this->layout->page->data 					= $data;
		$this->layout->page->person 				= $person;
		$this->layout->page->wleave 				= $wleave;

		$filters 									= 	[
															// ['prefix' => 'search', 'key' => 'status', 'value' => 'Cari Status ', 'values' => [['key' => 'contract', 'value' => 'Kontrak'], ['key' => 'trial', 'value' => 'Percobaan'], ['key' => 'internship', 'value' => 'Magang'], ['key' => 'permanent', 'value' => 'Tetap']]],
															['prefix' => 'sort', 'key' => 'start', 'value' => 'Urutkan Tanggal Mulai Berlaku Cuti', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]],
															['prefix' => 'sort', 'key' => 'end', 'value' => 'Urutkan Tanggal Kadaluarsa Cuti', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
														];

		$this->layout->page->filter 				= $filters;
		$this->layout->page->filtered 				= $filter;
		$this->layout->page->default_filter  		= ['org_id' => $data['id'], 'person_id' => $person['id']];

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
			$org_id 							= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.person.workleave.create', compact('id', 'data', 'person'));

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('type') && in_array(strtolower(Input::get('type')), ['given', 'taken']))
		{
			$type 								= strtolower(Input::get('type'));
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$begin 									= new DateTime( Input::get('start') );
		$end 									= new DateTime( Input::get('end').' + 1 day' );

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['WorkCalendar'] 				= true;
		$search['WithWorkCalendarSchedules'] 	= ['on' => [$begin->format('Y-m-d'), $end->format('Y-m-d')]];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success || !isset($contents->data->workscalendars[0]) || is_null($contents->data->workscalendars[0]) || $contents->data->workscalendars[0]=='')
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);

		$errors 								= new MessageBag();

		$interval 								= DateInterval::createFromDateString('1 day');
		$periods 								= new DatePeriod($begin, $interval, $end);

		$attributes 							= Input::only('notes');
		$attributes['work_id'] 					= $person['workscalendars'][0]['id'];
		$attributes['created_by'] 				= Session::get('loggedUser');

		DB::beginTransaction();

		if(Input::has('start'))
		{
			$begin 								= new DateTime( Input::get('start') );
			$ended 								= new DateTime( Input::get('end') );
			$maxend 							= new DateTime( Input::get('end').' + 7 days' );
		}
		else
		{
			$errors->add('Person', 'Tanggal Tidak Valid');
		}

		if(isset($ended) && ($ended->format('Y-m-d') < $begin->format('Y-m-d')))
		{
			$errors->add('Person', 'Tanggal akhir harus lebih besar atau sama dengan dari tanggal mulai.');
		}

		if(strtolower($type)=='given')
		{
			unset($search);
			unset($sort);

			$search['organisationid']			= $org_id;
			$search['id']						= Input::get('workleave_id');
			$search['active']					= true;
			$sort 								= ['name' => 'asc'];
			$results_2 							= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
			$contents_2 						= json_decode($results_2);

			if(!$contents_2->meta->success || !isset($contents->data->workscalendars[0]))
			{
				App::abort(404);
			}

			$attributes['workleave_id']			= $contents_2->data->id;
			$attributes['name']					= $contents_2->data->name;
			$attributes['status']				= $contents_2->data->status;
			$attributes['quota']				= $contents_2->data->quota;

			$attributes['start'] 				= $begin->format('Y-m-d');
			$attributes['end'] 					= date('Y-m-d', strtotime('last day of last month'));

			DB::beginTransaction();

			$content 							= $this->dispatch(new Saving(new PersonWorkleave, $attributes, $id, new Person, $person_id));
			$is_success 						= json_decode($content);
			
			if(!$is_success->meta->success)
			{
				foreach ($is_success->meta->errors as $key => $value) 
				{
					if(is_array($value))
					{
						foreach ($value as $key2 => $value2) 
						{
							$errors->add('Person', $value2);
						}
					}
					else
					{
						$errors->add('Person', $value);
					}
				}
			}
			elseif(strtoupper($is_success->data->status)=='CI')
			{
				//check apakah sudah ada CI sebelumnya
				if(!is_null($id))
				{
					unset($search);
					unset($sort);

					$search['parent']				= $id;
					$sort 							= ['name' => 'asc'];
					$results_2 						= $this->dispatch(new Getting(new PersonWorkleave, $search, $sort , 1, 1));
					$contents_2 					= json_decode($results_2);

					if(!$contents_2->meta->success || !isset($contents->data->workscalendars[0]))
					{
						App::abort(404);
					}
				
					$pid 						= $contents_2->data->id;
				}
				else
				{
					$pid 						= null;
				}

				unset($attributes);
				$attributes['id'] 				= $pid;
				$attributes['work_id'] 			= $is_success->data->work_id;
				$attributes['person_workleave_id'] 	= $is_success->data->id;
				$attributes['created_by'] 		= $is_success->data->created_by;
				$attributes['name'] 			= 'Pengambilan '.$is_success->data->name.' '.date('Y', strtotime($attributes['start']));
				$attributes['quota'] 			= 0 - (int)$is_success->data->quota;
				$attributes['status'] 			= $is_success->data->status;
				$attributes['notes'] 			= 'Pengambilan '.$is_success->data->name.' <> '.$is_success->data->notes;

				$batch 							= true;
			}
		}
		elseif(strtolower($type)=='taken')
		{
			$attributes['person_workleave_id']	= Input::get('person_workleave_id');
			
			if($attributes['person_workleave_id']==0)
			{
				$errors->add('Person', 'Pengambilan cuti harus menyertakan referensi pengambilan cuti');

				return Redirect::back()->withErrors($errors)->withInput();
			}

			Session::put('duedate', $begin->format('Y-m-d'));

			unset($search);
			unset($sort);

			$search['id'] 						= $attributes['person_workleave_id'];
			$sort 								= ['name' => 'asc'];
			$results 							= $this->dispatch(new Getting(new PersonWorkleave, $search, $sort , 1, 1));
			$contents 							= json_decode($results);
			$workleave 							= json_decode(json_encode($contents->data), true);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$attributes['name']					= 'Pengambilan '.$workleave['name'];
			$attributes['status']				= $workleave['status'];

			$batch 								= true;

		}

		if(isset($batch) && !$errors->count())
		{
			$attributes['associate_person_id'] 		= $person_id;
			$attributes['workscalendars'] 			= $person['workscalendars'][0]['calendar'];

			$queattr['created_by'] 					= Session::get('loggedUser');
			$queattr['process_name'] 				= 'hr:personworkleavebatch';
			$queattr['parameter'] 					= json_encode($attributes);
			$queattr['total_process'] 				= 1;
			$queattr['task_per_process']			= 1;
			$queattr['process_number'] 				= 0;
			$queattr['total_task'] 					= 1;
			$queattr['message'] 					= 'Initial Queue';

			$content 								= $this->dispatch(new Saving(new Queue, $queattr, null));
			$is_success_2 							= json_decode($content);

			if(!$is_success_2->meta->success)
			{
				foreach ($is_success_2->meta->errors as $key2 => $value2) 
				{
					if(is_array($value2))
					{
						foreach ($value2 as $key3 => $value3) 
						{
							$errors->add('Batch', $value3);
						}
					}
					else
					{
						$errors->add('Batch', $value2);
					}
				}
			}
		}
	
		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.person.workleaves.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_success', 'Cuti "' . $contents->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id)
	{
		// ---------------------- LOAD DATA ----------------------
		if(Input::has('org_id'))
		{
			$org_id 					= Input::get('org_id');
		}
		else
		{
			$org_id 					= Session::get('user.organisationid');
		}

		if(Input::has('branch_id'))
		{
			$branch_id 					= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search 						= ['id' => $id, 'branchid' => $branch_id, 'organisationid' => $org_id, 'withattributes' => ['branch', 'branch.organisation']];
		$results 						= $this->dispatch(new Getting(new Chart, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$chart 							= json_decode(json_encode($contents->data), true);
		$branch 						= $chart['branch'];
		$data 							= $chart['branch']['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 			= view('pages.chart.show');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->branch 	= $branch;
		$this->layout->pages->chart 	= $chart;
		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function destroy($id)
	{
		$attributes 						= ['username' => Session::get('user.username'), 'password' => Input::get('password')];

		$results 							= $this->dispatch(new Checking(new Person, $attributes));

		$content 							= json_decode($results);

		if($content->meta->success)
		{
			if(Input::has('org_id'))
			{
				$org_id 					= Input::get('org_id');
			}
			else
			{
				$org_id 					= Session::get('user.organisationid');
			}

			if(Input::has('person_id'))
			{
				$person_id 					= Input::get('person_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['id' => $person_id, 'organisationid' => $org_id, 'personworkleaveid' => $id];
			$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
			$contents 						= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}
			
			Session::put('duedate', date('Y-m-d', strtotime($contents->data->on)));

			DB::beginTransaction();

			$queattr['created_by'] 			= Session::get('loggedUser');
			$queattr['process_name'] 		= 'hr:personworkleavebatch';
			$queattr['process_option'] 		= 'delete';
			$queattr['parameter'] 			= json_encode(['id' => $id]);
			$queattr['task_per_process']	= 1;
			$queattr['process_number'] 		= 0;
			$queattr['message'] 			= 'Initial Queue';
			$queattr['total_process'] 		= 1;
			$queattr['total_task'] 			= 1;

			$content 						= $this->dispatch(new Saving(new Queue, $queattr, null));
			$is_success_2 					= json_decode($content);

			if(!$is_success_2->meta->success)
			{
				foreach ($is_success_2->meta->errors as $key2 => $value2) 
				{
					if(is_array($value2))
					{
						foreach ($value2 as $key3 => $value3) 
						{
							$errors->add('Batch', $value3);
						}
					}
					else
					{
						$errors->add('Batch', $value2);
					}
				}
			}

			if ($errors->count())
			{
				DB::rollback();
				return Redirect::back()->withErrors($errors);
			}
			else
			{
				DB::commit();
				return Redirect::route('hr.person.workleaves.index', [$person_id, 'org_id' => $org_id, 'person_id' => $person_id])->with('alert_success', 'Pengaturan Cuti sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}

	public function batchprogress()
	{
		$progress 						= [];
		$progress2 						= [];
		$pnumbers 						= [];
		$tprocesses 					= [];
		$pprocess 						= [];
		$search['createdbyid'] 			= Session::get('loggedUser');
		$search['running'] 				= null;
		$search['processname'] 			= 'hr:personworkleavebatch';

		$results 						= $this->dispatch(new Getting(new Queue, $search, ['created_at' => 'asc'], 1, 100));		
		$contents 	 					= json_decode($results);

		if (!$contents->meta->success)
		{
			return Response::json(['message' => $contents->meta->errors], 200);
		}

		foreach ($contents->data as $key => $value) 
		{
			$progress[$value->id]		= $value->process_number;
		}

		sleep(60);
		
		$results 						= $this->dispatch(new Getting(new Queue, $search, ['created_at' => 'asc'], 1, 100));		
		$contents 	 					= json_decode($results);

		if (!$contents->meta->success)
		{
			return Response::json(['message' => $contents->meta->errors], 200);
		}

		foreach ($contents->data as $key => $value) 
		{
			if(isset($progress[$value->id]) && $progress[$value->id]==$value->process_number)
			{
				$progress2[]			= 'Operasi berhenti pada '.$value->process_number.' / '.$value->total_process;
			}
			else
			{
				$progress2[]			= $value->message.' '.$value->process_number.' / '.$value->total_process;
			}

			$pnumbers[]					= $value->process_number;
			$tprocesses[]				= $value->total_process;
			$pprocess[] 				= $value->parameter;
		}

		return Response::json(['message' => $progress2, 'process_number' => $pnumbers, 'total_process' => $tprocesses, 'parameter_process' => $pprocess], 200);
	}
}