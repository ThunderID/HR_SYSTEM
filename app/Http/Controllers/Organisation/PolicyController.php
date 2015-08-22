<?php namespace App\Http\Controllers\Organisation;
use Input, Session, App, Paginator, Redirect, DB, Config;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Policy;

class PolicyController extends BaseController
{
	protected $controller_name = 'policy';

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

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , $page, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$filter 									= [];

		if(Input::has('q'))
		{
			$filter['search']['ondate']				= Input::get('q');
			$filter['active']['q']					= 'Cari Tanggal "'.Input::get('q').'"';
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
						case 'ondate':
							$active = 'Cari Tanggal ';
							break;

						default:
							$active = 'Cari Tanggal ';
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
						case 'start':
							$active = 'Urutkan Tanggal Aktif ';
							break;
						
						default:
							$active = 'Urutkan Tanggal Aktif ';
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

		$data 									= json_decode(json_encode($contents->data), true);
		$this->layout->page 					= view('pages.organisation.policy.index');
		$this->layout->page->controller_name 	= $this->controller_name;
		$this->layout->page->data 				= $data;
		$this->layout->page->filter 			= 	[
														['prefix' => 'sort', 'key' => 'start', 'value' => 'Urutkan Tanggal Aktif', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
													];
														
		$this->layout->page->filtered 			= $filter;
		$this->layout->page->default_filter  	= ['org_id' => $data['id']];

		$this->layout->page->route_back 		= route('hr.organisations.show', $org_id);

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

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.policy.create', compact('id', 'data'));
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

		$attributes 							= Input::only('policy_1', 'policy_2', 'margin_bottom_policy');
		$attributes['created_by']				= Session::get('loggedUser');
		if(Input::has('start'))
		{
			$attributes['start'] 				= date('Y-m-d', strtotime(Input::get('start')));
		}


		if (Input::has('firststatussettlement_year')||Input::has('firststatussettlement_month')||Input::has('firststatussettlement_day'))
		{
			$attributes['value']				= Input::get('firststatussettlement_year').Input::get('firststatussettlement_month').Input::get('firststatussettlement_day');
		}

		if (Input::has('secondstatussettlement_year')||Input::has('secondstatussettlement_month')||Input::has('secondstatussettlement_day'))
		{
			$attributes['value']				= Input::get('secondstatussettlement_year').Input::get('secondstatussettlement_month').Input::get('secondstatussettlement_day');
		}

		if (Input::has('extendsworkleave_year')||Input::has('extendsworkleave_month')||Input::has('extendsworkleave_day'))
		{
			$attributes['value']				= Input::get('extendsworkleave_year').Input::get('extendsworkleave_month').Input::get('extendsworkleave_day');
		}

		if (Input::has('extendsmidworkleave_year')||Input::has('extendsmidworkleave_month')||Input::has('extendsmidworkleave_day'))
		{
			$attributes['value']				= Input::get('extendsmidworkleave_year').Input::get('extendsmidworkleave_month').Input::get('extendsmidworkleave_day');
		}

		if (Input::has('firstacleditor_year')||Input::has('firstacleditor_month')||Input::has('firstacleditor_day'))
		{
			$attributes['value']				= Input::get('firstacleditor_year').Input::get('firstacleditor_month').Input::get('firstacleditor_day');
		}

		if (Input::has('secondacleditor_year')||Input::has('secondacleditor_month')||Input::has('secondacleditor_day'))
		{
			$attributes['value']				= Input::get('secondacleditor_year').Input::get('secondacleditor_month').Input::get('secondacleditor_day');
		}




		$attributes['margin_bottom_policy']		= (Input::get('margin_bottom_policy')*60);
		$attributes['policy_1']					= (Input::get('policy_1')*60);
		$attributes['policy_2']					= (Input::get('policy_2')*60);
		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Policy, $attributes, $id, new Organisation, $org_id));
		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Policy', $value2);
					}
				}
				else
				{
					$errors->add('Policy', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.policys.index', ['org_id' => $org_id])->with('alert_success', 'Policy "' . $is_success->data->start. '" sudah disimpan');
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
		$results 						= $this->dispatch(new Getting(new Policy, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$policy 						= json_decode(json_encode($contents->data), true);
		$data 							= $policy['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 				= view('pages.organisation.policy.show');
		$this->layout->pages->data 			= $data;
		$this->layout->pages->policy 		= $policy;
		$this->layout->pages->route_back 	= route('hr.organisation.policys.index', ['org_id' => $org_id]);

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

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'organisationid' => $org_id, 'withattributes' => ['organisation']];
			$results 						= $this->dispatch(new Getting(new Policy, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Policy, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.policys.index', ['org_id' => $org_id])->with('alert_success', 'Pengaturan Policy "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}