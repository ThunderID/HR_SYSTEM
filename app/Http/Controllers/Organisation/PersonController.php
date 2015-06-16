<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Work;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\Contact;
use App\Models\PersonSchedule;

class PersonController extends BaseController 
{

	protected $controller_name 						= 'personalia';

	public function index()
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
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);

		$this->layout->page 						= view('pages.person.index', compact('data'));

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

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$search['id']							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		$this->layout->page 					= view('pages.person.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}
		
		$attributes 							= Input::only('uniqid','name', 'prefix_title', 'suffix_title', 'gender', 'date_of_birth', 'place_of_birth');

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		// if(!in_array($org_id, Session::get('user.orgids')))
		// {
		// 	App::abort(404);
		// }

		$errors 								= new MessageBag();

		if(Input::has('password'))
		{
			$attributes['password']				= Hash::make($attributes['password']);
		}

		DB::beginTransaction();
		$content 								= $this->dispatch(new Saving(new Person, $attributes, $id, new Organisation, $org_id));

		$is_success 							= json_decode($content);
		if(!$is_success->meta->success)
		{
			$errors->add('Person', $is_success->meta->errors);
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.persons.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('alert_success', 'Data "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show()
	{
		$this->layout->page 	= view('pages.person.show');

		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

}
