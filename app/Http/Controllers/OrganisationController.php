<?php namespace App\Http\Controllers;

use Input, Session, App, Paginator, Redirect, DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Models\Work;
use App\Models\Person;

class OrganisationController extends BaseController 
{

	protected $controller_name 					= 'organisasi';

	public function index()
	{		
		$this->layout->page 					= view('pages.organisation.index');

		return $this->layout;
	}

	public function create($id = null)
	{
		$this->layout->page 					= view('pages.organisation.create', compact('id'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}
		
		$attributes 							= Input::only('name');
		$person 								= 1;//Session::get('user.id');
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();
		
		$content 								= $this->dispatch(new Saving(new Organisation, $attributes, $id));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			$errors->add('Organisation', $is_success->meta->errors);
		}

		if(is_null($id))
		{
			$content_2								= $this->dispatch(new Getting(new Organisation, ['ID' => $is_success->data->id, 'withattributes' => ['branches', 'branches.charts', 'branches.charts.calendars']], ['created_at' => 'asc'] ,1, 1));

			$is_success_2 							= json_decode($content_2);

			if(!$is_success_2->meta->success || !isset($is_success_2->data->branches[0]) || !isset($is_success_2->data->branches[0]->charts[0]))
			{
				$errors->add('Organisation', $is_success_2->meta->errors);
			}

			$work['chart_id'] 						= $is_success_2->data->branches[0]->charts[0]->id;
			$work['status'] 						= 'admin';
			$work['position'] 						= 'admin';
			$work['start'] 							= date('Y-m-d');

			$saved_work 							= $this->dispatch(new Saving(new Work, $work, null, new Person, $person));
			$is_success_3 							= json_decode($saved_work);
			
			if(!$is_success_3->meta->success)
			{
				$errors->add('Organisation', $is_success_3->meta->errors);
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.organisations.show', [$is_success->data->id, 'org_id' => $is_success->data->id])->with('alert_success', 'organisasi "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show()
	{
		$this->layout->page 						= view('pages.organisation.show');

		return $this->layout;
	}

	public function edit($id)
	{
		$this->layout->page 						= view('pages.organisation.create', compact('id'));

		return $this->layout;
	}

}
