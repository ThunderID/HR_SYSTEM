<?php namespace App\Http\Controllers\Organisation\Branch\Chart;
use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Chart;
use App\Models\Authentication;
use App\Models\Menu;

class AuthenticationController extends BaseController
{
	protected $controller_name = 'otentikasi';

	public function index($page = 1)
	{
		// ---------------------- LOAD DATA ----------------------
		if(Input::has('org_id'))
		{
			$org_id 					= Input::get('org_id');
		}
		else
		{
			$org_id 					= Session::get('user.organisation');
		}

		if(Input::has('branch_id'))
		{
			$branch_id 					= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('chart_id'))
		{
			$id 						= Input::get('chart_id');
		}
		else
		{
			App::abort(404);
		}
		
		// if(!in_array($org_id, Session::get('user.organisationids')))
		// {
		// App::abort(404);
		// }
		
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
		$this->layout->pages 			= view('pages.authentication.index');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->branch 	= $branch;
		$this->layout->pages->chart 	= $chart;
		
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
			$org_id 							= Session::get('user.organisation');
		}

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('chart_id'))
		{
			$chart_id 							= Input::get('chart_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('menu_id'))
		{
			$menu_id 							= Input::get('menu_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('auth_id'))
		{
			$id 								= Input::get('auth_id');
		}
		else
		{
			$id 								= null;
		}

		$search['id'] 							= $chart_id;
		$search['branchid'] 					= $branch_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		if(Input::has('right'))
		{
			$attributes[strtolower(Input::get('right'))]= false;
		}
		elseif(Input::has('wrong'))
		{
			$attributes[strtolower(Input::get('wrong'))]= true;
		}

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$attributes['chart_id']					= $chart_id;

		$content 								= $this->dispatch(new Saving(new Authentication, $attributes, $id, new Menu, $menu_id));
		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Chart', $value2);
					}
				}
				else
				{
					$errors->add('Chart', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.chart.authentications.index', ['chart_id' => $chart_id, 'branch_id' => $branch_id, 'org_id' => $org_id])->with('alert_success', 'Otentikasi Jabatan "' . $contents->data->name. '" sudah disimpan');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}
}