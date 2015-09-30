<?php namespace App\Http\Controllers\Organisation\Branch;
use Input, Session, App, Paginator, Redirect, DB, Config;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Chart;
use App\Models\Branch;
use App\Models\Person;
use App\Models\Follow;
use App\Models\Calendar;

class ChartController extends BaseController
{
	protected $controller_name = 'jabatan';

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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $branch_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , $page, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$branch 								= json_decode(json_encode($contents->data), true);
		$data 									= $branch['organisation'];

		$filter 								= [];
		if(Input::has('q'))
		{
			$filter['search']['name']			= Input::get('q');
			$filter['active']['q']				= 'Cari Nama "'.Input::get('q').'"';
		}
		if(Input::has('filter'))
		{
			$dirty_filter 						= Input::get('key');
			$dirty_filter_value 				= Input::get('value');
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
							$active = 'Cari Nama';
							break;
						case 'tag':
							$active = 'Cari Departemen ';
							break;						
						default:
							$active = 'Cari Nama';
							break;
					}

					switch (strtolower($dirty_filter_value[$key])) 
					{
						case 'asc':
							$active = $active.'"'.$dirty_filter_value[$key].'"';
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
						case 'tag':
							$active = 'Urutkan Departemen';
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
		
		unset($search);
		unset($sort);
		
		$search['organisationid']					= $org_id;
		$search['grouptag']							= true;
		$sort 										= ['path' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 100));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$doctags 									= json_decode(json_encode($contents->data), true);

		$tags 										= [];
		foreach ($doctags as $key => $value) 
		{
			$tags[$key]['key']						= $value['tag'];
			$tags[$key]['value']					= $value['tag'];
		}

		$this->layout->page 					= view('pages.organisation.branch.chart.index');
		$this->layout->page->controller_name 	= $this->controller_name;
		$this->layout->page->data 				= $data;
		$this->layout->page->branch 			= $branch;
		$this->layout->page->filter 			= 	[
														['prefix' => 'search', 'key' => 'tag', 'value' => 'Departemen', 'values' => $tags],
														// ['prefix' => 'sort', 'key' => 'name', 'value' => 'Urutkan Nama', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]],
														// ['prefix' => 'sort', 'key' => 'tag', 'value' => 'Urutkan Department', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
													];
		$this->layout->page->filtered 			= $filter;
		$this->layout->page->default_filter 	= ['org_id' => $data['id'], 'branch_id' => $branch['id']];

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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $branch_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$branch 								= json_decode(json_encode($contents->data), true);
		$data 									= $branch['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.branch.chart.create', compact('id', 'data', 'branch'));

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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $branch_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('name', 'tag', 'min_employee', 'max_employee', 'ideal_employee');

		if(Input::has('chart_id'))
		{
			$attributes['chart_id']				= Input::get('chart_id');
		}

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Chart, $attributes, $id, new Branch, $branch_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Branch', $value2);
					}
				}
				else
				{
					$errors->add('Branch', $value);
				}
			}
		}
		elseif(Input::has('affect'))
		{
			unset($search);
			unset($sort);

			$search['organisationid'] 				= $org_id;
			$sort 									= ['name' => 'asc'];
			$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 100));
			$contents_c 							= json_decode($results);

			if(!$contents_c->meta->success)
			{
				App::abort(404);
			}

			$calendars 								= json_decode(json_encode($contents_c->data), true);

			foreach ($calendars as $key => $value) 
			{
				unset($attributes);
				$attributes['chart_id']				= $is_success->data->id;

				$content 							= $this->dispatch(new Saving(new Follow, $attributes, $id, new Calendar, $value['id']));
				$is_success_2 						= json_decode($content);

				if(!$is_success_2->meta->success)
				{
					foreach ($is_success_2->meta->errors as $key2 => $value2) 
					{
						if(is_array($value2))
						{
							foreach ($value2 as $key3 => $value3) 
							{
								$errors->add('Chart', $value3);
							}
						}
						else
						{
							$errors->add('Chart', $value2);
						}
					}
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.branch.charts.index', ['branch_id' => $branch_id, 'org_id' => $org_id])->with('alert_success', 'Struktur Organisasi cabang "' . $contents->data->name. '" sudah disimpan');
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
		$this->layout->pages 			= view('pages.organisation.branch.chart.show');
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

			$search 						= ['id' => $id, 'organisationid' => $org_id, 'branchid' => $branch_id];
			$results 						= $this->dispatch(new Getting(new Chart, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Chart, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.branch.charts.index', ['org_id' => $org_id, 'branch_id' => $branch_id])->with('alert_success', 'Struktur Organisasi "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}