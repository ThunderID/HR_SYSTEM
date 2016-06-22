<?php namespace App\Http\Controllers\Organisation\Person;

use Input, Session, App, Paginator, Redirect, DB, Config, Response, Excel, Validator;

use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;

use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;

use App\Models\Work;
use App\Models\Person;
use App\Models\Workleave;
use App\Models\Follow;
use App\Models\FollowWorkleave;
use App\Models\Queue;

class WorkController extends BaseController
{
	protected $controller_name = 'karir';

	public function index($id = null)
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

		$search['id'] 								= $person_id;
		$search['organisationid'] 					= $org_id;
		$search['withattributes'] 					= ['organisation'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 				= [Session::get('user.chartpath'), Session::get('user.workid')];
		}
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 									= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];
		
		$filter 									= [];
		if(Input::has('q'))
		{
			$filter['search']['status']				= Input::get('q');
			$filter['active']['q']					= 'Cari Status "'.Input::get('q').'"';
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
						case 'start':
							$active = 'Cari Pekerjaan ';
							break;

						default:
							$active = 'Cari Pekerjaan ';
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
							$active = 'Urutkan Tanggal Mulai Bekerja';
							break;
						
						case 'end':
							$active = 'Urutkan Tanggal Berhenti Bekerja';
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
		
		$this->layout->page 						= view('pages.organisation.person.work.index');
		$this->layout->page->controller_name 		= $this->controller_name;
		$this->layout->page->data 					= $data;
		$this->layout->page->person 				= $person;

		$filters 									= 	[
															['prefix' => 'search', 'key' => 'status', 'value' => 'Cari Status ', 'values' => [['key' => 'contract', 'value' => 'Kontrak'], ['key' => 'trial', 'value' => 'Percobaan'], ['key' => 'internship', 'value' => 'Magang'], ['key' => 'permanent', 'value' => 'Tetap']]],
															['prefix' => 'sort', 'key' => 'start', 'value' => 'Urutkan Tanggal Mulai Bekerja', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]],
															['prefix' => 'sort', 'key' => 'end', 'value' => 'Urutkan Tanggal Berhenti Bekerja', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
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
		$this->layout->pages 					= view('pages.organisation.person.work.create', compact('id', 'data', 'person'));

		return $this->layout;
	}
	
	public function store($id = null)
	{
		$errors 								= new MessageBag();
		
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
		elseif(!Input::has('import'))
		{
			App::abort(404);
		}

		if (Input::has('workleave_id'))
		{
			$workleave_id 						= Input::get('workleave_id');
		}
		elseif(is_null($id))
		{
			$errors->add('Work', 'Cuti tidak boleh kosong');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('import'))
		{
			if (Input::hasFile('file_csv')) 
			{
				$validator = Validator::make(['file' => Input::file('file_csv'), 
												'extension' => strtolower(Input::file('file_csv')->getClientOriginalExtension())], 
											['file' => 'required|max:20', 'extension' => 'required|in:csv']);
				
				if (!$validator->passes())
				{
					return Redirect::back()->withErrors($validator->errors())->withInput();
				}

				$file_csv 		= Input::file('file_csv');
				$attributes = [];				
				$sheet = Excel::load($file_csv)->toArray();				
				
				$queattr['created_by'] 					= Session::get('loggedUser');
				$queattr['process_name'] 				= 'hr:workbatch';
				$queattr['parameter'] 					= json_encode(['csv' => $sheet]);
				$queattr['total_process'] 				= count($sheet);
				$queattr['task_per_process']			= 1;
				$queattr['process_number'] 				= 0;
				$queattr['total_task'] 					= count($sheet);
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

				return Redirect::back()->with('alert_info', 'Data sedang disimpan');
			}
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		if(Input::has('chart_id'))
		{
			$attributes 						= Input::only('chart_id', 'calendar_id', 'start', 'reason_end_job', 'status', 'grade');

			if((int)$attributes['calendar_id']==0)
			{
				App::abort(404);
			}
		}
		else
		{
			$attributes 						= Input::only('position', 'organisation', 'start', 'reason_end_job', 'status');
		}

		$attributes['start'] 					= date('Y-m-d', strtotime($attributes['start']));

		if(Input::has('end') && !is_null(Input::get('end')))
		{
			$attributes['end'] 					= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$attributes['end']					= null;
		}

		if (Input::has('is_absence'))
		{
			$attributes['is_absence']			= true;
		}
		else
		{
			$attributes['is_absence']			= false;
		}

		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new Work, $attributes, $id, new Person, $person_id));
		$is_success 							= json_decode($content);
		
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

		// Associate FollowWorkleave to Workleave
		if(is_null($id) && !$errors->count())
		{
			$attributes2						= ['work_id' => $is_success->data->id];
			$content_2 							= $this->dispatch(new Saving(new FollowWorkleave, $attributes2, null, new Workleave, $workleave_id));
			$is_success_2 						= json_decode($content_2);

			if(!$is_success_2->meta->success)
			{
				foreach ($is_success_2->meta->errors as $key => $value) 
				{
					if(is_array($value))
					{
						foreach ($value as $key2 => $value2) 
						{
							$errors->add('Workleave', $value2);
						}
					}
					else
					{
						$errors->add('Workleave', $value);
					}
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.person.works.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_success', 'Pekerjaan karyawan "' . $contents->data->name. '" sudah disimpan');
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

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}
		
		$search 						= ['id' => $id, 'organisationid' => $org_id, 'withattributes' => ['organisation']];
		$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 						= json_decode(json_encode($contents->data), true);
		$data 							= $person['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 			= view('pages.organisation.person.work.show');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->person 	= $person;
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

			$search 						= ['organisationid' => $org_id, 'id' => $person_id];
			$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'personid' => $person_id];
			$results 						= $this->dispatch(new Getting(new Work, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Work, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.person.works.index', ['org_id' => $org_id, 'person_id' => $person_id])->with('alert_success', 'Pekerjaan sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}

	public function ajax($page = 1)
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

		if(Input::has('term'))
		{
			$chartid 					= Input::get('term');
		}
		else
		{
			return Response::json(['message' => 'Not Found'], 404);
		}
	
		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			return Response::json(['message' => 'Not Found'], 404);
		}
		
		$search 						= ['chartid' => (int)$chartid, 'withattributes' => ['calendar']];
		$results 						= $this->dispatch(new Getting(new Follow, $search, [] , 1, 100));
		$contents 						= json_decode($results);	
		$data_follow					= json_decode(json_encode($contents->data), true);	
		
		if(!$contents->meta->success)
		{
			return Response::json(['message' => 'Not Found'], 404);
		}

		return Response::json($data_follow, 200);
	}
}