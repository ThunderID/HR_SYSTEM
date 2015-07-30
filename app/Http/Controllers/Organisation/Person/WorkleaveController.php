<?php namespace App\Http\Controllers\Organisation\Person;
use Input, Session, App, Paginator, Redirect, DB, Config;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Person;
use App\Models\Workleave;
use App\Models\PersonWorkleave;

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

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['currentwork'] 					= null;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success || !isset($contents->data->works[0]) || is_null($contents->data->works[0]) || $contents->data->works[0]=='')
		{
			App::abort(404);
		}

		$errors 								= new MessageBag();

		$attributes 							= Input::only('notes');

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

			if(!$contents_2->meta->success || !isset($contents->data->works[0]))
			{
				App::abort(404);
			}

			$attributes['workleave_id']			= $contents_2->data->id;
			$attributes['name']					= $contents_2->data->name;
			$attributes['status']				= $contents_2->data->status;
			$attributes['quota']				= $contents_2->data->quota;
		}
		elseif(strtolower($type)=='taken')
		{
			$attributes['person_workleave_id']	= Input::get('person_workleave_id');
			
			if($attributes['person_workleave_id']==0)
			{
				$errors->add('Person', 'Pengambilan cuti harus menyertakan referensi pengambilan cuti');

				return Redirect::back()->withErrors($errors)->withInput();
			}

			$attributes['name']					= Input::get('name');
			$attributes['status']				= 'CONFIRMED';
			$attributes['quota']				= Input::get('quota');
		}

		if(Input::has('start'))
		{
			$attributes['start'] 				= date('Y-m-d', strtotime(Input::get('start')));
		}
		if(Input::has('end'))
		{
			$attributes['end'] 					= date('Y-m-d', strtotime(Input::get('end')));
		}

		$attributes['work_id'] 					= $contents->data->works[0]->pivot->id;
		$attributes['created_by'] 				= Session::get('loggedUser');

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new PersonWorkleave, $attributes, $id, new Person, $person_id));
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

			$results 						= $this->dispatch(new Deleting(new PersonWorkleave, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.person.workleaves.index', [$person_id, 'org_id' => $org_id, 'person_id' => $person_id])->with('alert_success', 'Jatah Cuti sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}