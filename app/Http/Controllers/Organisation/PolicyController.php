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

		$p 										= null;

		$types 									= ['passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle', 'extendsworkleave', 'extendsmidworkleave', 'firstacleditor', 'secondacleditor'];
	
		foreach(range(0, count($types)-1) as $index)
		{
			unset($search);
			unset($sort);
			
			$search['organisationid'] 			= $org_id;
			$search['type'] 					= $types[$index];
			$search['ondate'] 					= date('Y-m-d');
			$sort 								= ['started_at' => 'desc'];
			$results 							= $this->dispatch(new Getting(new Policy, $search, $sort , 1, 1));
			$contents 							= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}
			else
			{
				$p[strtolower($types[$index])] 	= $contents->data->value;	
			}

		}

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.policy.create', compact('id', 'data', 'p'));
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

		$types 									= ['passwordreminder', 'assplimit', 'ulsplimit', 'hpsplimit', 'htsplimit', 'hcsplimit', 'firststatussettlement', 'secondstatussettlement', 'firstidle', 'secondidle','thirdidle', 'extendsworkleave', 'extendsmidworkleave', 'firstacleditor', 'secondacleditor'];
	
		foreach(range(0, count($types)-1) as $index)
		{
			unset($search);
			unset($sort);
			
			$search['organisationid'] 			= $org_id;
			$search['type'] 					= $types[$index];
			$sort 								= ['started_at' => 'asc'];
			$results 							= $this->dispatch(new Getting(new Policy, $search, $sort , 1, 1));
			$contents 							= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}
			else
			{
				$p[strtolower($types[$index])] 	= $contents->data->value;	
			}
		}
		
		if (Input::has('passwordreminder'))
		{
			$cp['passwordreminder']				= str_replace('0','',Input::get('passwordreminder'));
			if(strtotime($cp['passwordreminder']) != strtotime($p['passwordreminder']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'passwordreminder';
				$attr['value']			= $cp['passwordreminder'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('assplimit'))
		{
			$cp['assplimit']					= str_replace('0','',Input::get('assplimit'));
			if((int)$cp['assplimit'] != (int)$p['assplimit'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'assplimit';
				$attr['value']			= $cp['assplimit'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('ulsplimit'))
		{
			$cp['ulsplimit']					= str_replace('0','',Input::get('ulsplimit'));
			if((int)$cp['ulsplimit'] != (int)$p['ulsplimit'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'ulsplimit';
				$attr['value']			= $cp['ulsplimit'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('hpsplimit'))
		{
			$cp['hpsplimit']					= str_replace('0','',Input::get('hpsplimit'));
			if((int)$cp['hpsplimit'] != (int)$p['hpsplimit'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'hpsplimit';
				$attr['value']			= $cp['hpsplimit'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('htsplimit'))
		{
			$cp['htsplimit']					= str_replace('0','',Input::get('htsplimit'));
			if((int)$cp['htsplimit'] != (int)$p['htsplimit'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'htsplimit';
				$attr['value']			= $cp['htsplimit'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('hcsplimit'))
		{
			$cp['hcsplimit']					= str_replace('0','',Input::get('hcsplimit'));
			if((int)$cp['hcsplimit'] != (int)$p['hcsplimit'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'hcsplimit';
				$attr['value']			= $cp['hcsplimit'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('firststatussettlement_year')||Input::has('firststatussettlement_month')||Input::has('firststatussettlement_day'))
		{
			$cp['firststatussettlement']		= str_replace('0','',Input::get('firststatussettlement_year').' '.Input::get('firststatussettlement_month').' '.Input::get('firststatussettlement_day'));
			if(strtotime($cp['firststatussettlement']) != strtotime($p['firststatussettlement']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'firststatussettlement';
				$attr['value']			= $cp['firststatussettlement'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('secondstatussettlement_year')||Input::has('secondstatussettlement_month')||Input::has('secondstatussettlement_day'))
		{
			$cp['secondstatussettlement']		= str_replace('0','',Input::get('secondstatussettlement_year').' '.Input::get('secondstatussettlement_month').' '.Input::get('secondstatussettlement_day'));
			if(strtotime($cp['secondstatussettlement']) != strtotime($p['secondstatussettlement']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'secondstatussettlement';
				$attr['value']			= $cp['secondstatussettlement'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('extendsworkleave_year')||Input::has('extendsworkleave_month')||Input::has('extendsworkleave_day'))
		{
			$cp['extendsworkleave']		= str_replace('0','',Input::get('extendsworkleave_year').' '.Input::get('extendsworkleave_month').' '.Input::get('extendsworkleave_day'));
			if(strtotime($cp['extendsworkleave']) != strtotime($p['extendsworkleave']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'extendsworkleave';
				$attr['value']			= $cp['extendsworkleave'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('extendsmidworkleave_year')||Input::has('extendsmidworkleave_month')||Input::has('extendsmidworkleave_day'))
		{
			$cp['extendsmidworkleave']		= str_replace('0','',Input::get('extendsmidworkleave_year').' '.Input::get('extendsmidworkleave_month').' '.Input::get('extendsmidworkleave_day'));
			if(strtotime($cp['extendsmidworkleave']) != strtotime($p['extendsmidworkleave']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'extendsmidworkleave';
				$attr['value']			= $cp['extendsmidworkleave'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('firstacleditor_year')||Input::has('firstacleditor_month')||Input::has('firstacleditor_day'))
		{
			$cp['firstacleditor']		= str_replace('0','',Input::get('firstacleditor_year').' '.Input::get('firstacleditor_month').' '.Input::get('firstacleditor_day'));
			if(strtotime($cp['firstacleditor']) != strtotime($p['firstacleditor']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'firstacleditor';
				$attr['value']			= $cp['firstacleditor'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('secondacleditor_year')||Input::has('secondacleditor_month')||Input::has('secondacleditor_day'))
		{
			$cp['secondacleditor']		= str_replace('0','',Input::get('secondacleditor_year').' '.Input::get('secondacleditor_month').' '.Input::get('secondacleditor_day'));
			if(strtotime($cp['secondacleditor']) != strtotime($p['secondacleditor']))
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'secondacleditor';
				$attr['value']			= $cp['secondacleditor'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('firstidle_hour')||Input::has('firstidle_minute'))
		{
			$fih						= (int)(Input::get('firstidle_hour')*3600);
			$fim						= (int)(Input::get('firstidle_minute')*60);
			$cp['firstidle'] 			= $fih+$fim;
			if($cp['firstidle'] != $p['firstidle'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'firstidle';
				$attr['value']			= $cp['firstidle'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('secondidle_hour')||Input::has('secondidle_minute'))
		{
			$fih						= (int)(Input::get('secondidle_hour')*3600);
			$fim						= (int)(Input::get('secondidle_minute')*60);
			$cp['secondidle'] 			= $fih+$fim;
			if($cp['secondidle'] != $p['secondidle'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'secondidle';
				$attr['value']			= $cp['secondidle'];
				$attributes[]			= $attr;
			}
		}

		if (Input::has('thirdidle_hour')||Input::has('thirdidle_minute'))
		{
			$fih						= (int)(Input::get('thirdidle_hour')*3600);
			$fim						= (int)(Input::get('thirdidle_minute')*60);
			$cp['thirdidle'] 			= $fih+$fim;
			if($cp['thirdidle'] != $p['thirdidle'])
			{
				$attr['created_by']		= Session::get('loggedUser');
				$attr['started_at']		= date('Y-m-d H:i:s');
				$attr['type']			= 'thirdidle';
				$attr['value']			= $cp['thirdidle'];
				$attributes[]			= $attr;
			}
		}

		$errors 								= new MessageBag();

		DB::beginTransaction();

		if(isset($attributes))
		{
			foreach ($attributes as $key2 => $value2) 
			{
				$content 						= $this->dispatch(new Saving(new Policy, $value2, null, new Organisation, $org_id));
				$is_success 					= json_decode($content);

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
			}
			
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.policies.index', ['org_id' => $org_id])->with('alert_success', 'Perubahan kebijakan sudah disimpan');
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