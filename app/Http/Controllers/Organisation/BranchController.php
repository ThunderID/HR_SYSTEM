<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Work;
use App\Models\Person;
use App\Models\Branch;

class BranchController extends BaseController 
{

	protected $controller_name 					= 'cabang';

	public function index($page = 1)
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$search['id']								= $org_id;
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , $page, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);


		/*--------------------------------------- LOAD DATA ---------------------------------*/
		$search										= ['organisationid' => $org_id];
		if(Input::has('q'))
		{
			if(Input::has('field'))
			{
				$search[Input::get('field')]		= Input::get('q');			
			}
			else
			{
				$search['name']						= Input::get('q');			
			}
		}

		if(Input::has('sort_name'))
		{
			$sort['name']							= Input::get('sort_name');			
		}
		else
		{
			$sort 									= ['name' => 'asc'];
		}


		$results 									= $this->dispatch(new Getting(new Branch, $search, $sort , $page, 12));		

		$contents 									= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}
		
		$branches 									= json_decode(json_encode($contents->data), true);


		if(Input::has('q'))
		{
			$this->layout->page_title 				= 'Hasil Pencarian "'.Input::get('q').'"';
		}

		$this->layout->page 					= view('pages.branch.index');
		$this->layout->page->controller_name 		= $this->controller_name;
		$this->layout->page->data 					= $data;
		$this->layout->page->branches 				= $branches;
		// $this->layout->page->paginator 				= $paginator;
		$this->layout->page->route 					= ['org_id' => $data['id']];

		return $this->layout;
	}

	public function create($id = null)
	{
		$this->layout->page 					= view('pages.branch.create', compact('id'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}
		
		// $attributes 							= Input::only('name');
		// $person 								= 1;//Session::get('user.id');
		
		// $errors 								= new MessageBag();
		
		// DB::beginTransaction();
		
		// $content 								= $this->dispatch(new Saving(new Organisation, $attributes, $id));

		// $is_success 							= json_decode($content);

		// if(!$is_success->meta->success)
		// {
		// 	$errors->add('Organisation', $is_success->meta->errors);
		// }

		// if(is_null($id))
		// {
		// 	$content_2								= $this->dispatch(new Getting(new Organisation, ['ID' => $is_success->data->id, 'withattributes' => ['branches', 'branches.charts', 'branches.charts.calendars']], ['created_at' => 'asc'] ,1, 1));

		// 	$is_success_2 							= json_decode($content_2);

		// 	if(!$is_success_2->meta->success || !isset($is_success_2->data->branches[0]) || !isset($is_success_2->data->branches[0]->charts[0]))
		// 	{
		// 		$errors->add('Organisation', $is_success_2->meta->errors);
		// 	}

		// 	$work['chart_id'] 						= $is_success_2->data->branches[0]->charts[0]->id;
		// 	$work['status'] 						= 'admin';
		// 	$work['position'] 						= 'admin';
		// 	$work['start'] 							= date('Y-m-d');

		// 	$saved_work 							= $this->dispatch(new Saving(new Work, $work, null, new Person, $person));
		// 	$is_success_3 							= json_decode($saved_work);
			
		// 	if(!$is_success_3->meta->success)
		// 	{
		// 		$errors->add('Organisation', $is_success_3->meta->errors);
		// 	}
		// }

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.branches.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('alert_success', 'Cabang "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($content->meta->errors)->withInput();
	}

	public function show()
	{
		$this->layout->page 	= view('pages.branch.show');

		return $this->layout;
	}

	public function edit($id)
	{
		$this->layout->page 					= view('pages.branch.create', compact('id'));

		return $this->layout;
	}

}
