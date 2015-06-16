<?php namespace App\Http\Controllers\Organisation\Branch;
use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Chart;
use App\Models\Branch;

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

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// App::abort(404);
		// }

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
		$this->layout->page 					= view('pages.chart.index');
		$this->layout->page->controller_name 	= $this->controller_name;
		$this->layout->page->data 				= $data;
		$this->layout->page->branch 			= $branch;
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

		if(Input::has('branch_id'))
		{
			$branch_id 							= Input::get('branch_id');
		}
		else
		{
			App::abort(404);
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// App::abort(404);
		// }

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
		$this->layout->pages 					= view('pages.chart.create', compact('id', 'data', 'branch'));

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

		$search['id'] 							= $branch_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('name', 'tag', 'grade', 'min_employee', 'max_employee', 'ideal_employee', 'path');

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Chart, $attributes, $id, new Branch, $branch_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			$errors->add('Branch', $is_success->meta->errors);
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.branches.show', [$branch_id, 'org_id' => $org_id])->with('alert_success', 'Kontak cabang "' . $contents->data->name. '" sudah disimpan');
		}
		
		DB::rollback();
		return Redirect::back()->withErrors($content->meta->errors)->withInput();
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
			$org_id 					= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// App::abort(404);
		// }
		
		$search 						= ['id' => $id, 'organisationid' => $org_id, 'withattributes' => ['organisation']];
		$results 						= $this->dispatch(new Getting(new Branch, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$branch 						= json_decode(json_encode($contents->data), true);
		$data 							= $branch['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 			= view('pages.branch.show');
		$this->layout->pages->data 		= $data;
		$this->layout->pages->branch 	= $branch;
		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}
}