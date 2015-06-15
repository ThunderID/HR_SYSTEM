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

		if(isset($attributes['works']))
		{
			foreach ($attributes['works'] as $key => $value) 
			{
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$work_id					= $value['id'];
				}
				else
				{
					$work_id					= null;
				}
				$saved_work 					= $this->dispatch(new Saving(new Work, $value, $work_id, new Person, $is_success->data->id));
				$is_success_2 					= json_decode($saved_work);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Person', $is_success_2->meta->errors);
				}
			}
		}

		if(isset($attributes['documents']))
		{
			foreach ($attributes['documents'] as $key => $value) 
			{
				$attributes['document_id']		= $value['document']['document_id'];
				if(isset($value['document']['id']) && $value['document']['id']!='' && !is_null($value['document']['id']))
				{
					$attributes['id']			= $value['document']['id'];
				}
				else
				{
					$attributes['id']			= null;
				}
				$saved_document 				= $this->dispatch(new Saving(new PersonDocument, $attributes, $attributes['id'], new Person, $is_success->data->id));
				$is_success_2 					= json_decode($saved_document);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Person', $is_success_2->meta->errors);
				}
				foreach ($attributes['documents'][$key]['details'] as $key2 => $value2) 
				{
					$attributes_2['template_id']	= $value2['template_id'];
					if((int)($value2['value']))
					{
						$attributes_2['numeric'] = $value2['value'];
					}
					else
					{
						$attributes_2['text']			= $value2['value'];
					}
					if(isset($value2['id']) && $value2['id']!='' && !is_null($value2['id']))
					{
						$attributes_2['id']		= $value2['id'];
					}
					else
					{
						$attributes_2['id']		= null;
					}
					$saved_detail 				= $this->dispatch(new Saving(new DocumentDetail, $attributes_2, $attributes_2['id'], new PersonDocument, $is_success_2->data->id));
					$is_success_3 				= json_decode($saved_detail);
					if(!$is_success_3->meta->success)
					{
						$errors->add('Person', $is_success_3->meta->errors);
					}	
				}
			}
		}

		if(isset($attributes['relatives']))
		{
			foreach ($attributes['relatives'] as $key => $value) 
			{
				if(isset($value['id']))
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, $value['id'], new Person, $is_success->data->id, ['relationship' => $value['relationship']]));
				}
				else
				{
					$saved_relative 		= $this->dispatch(new Saving(new Person, $value, null, new Person, $is_success->data->id, ['relationship' => $value['relationship']]));
				}

				$is_success_2 				= json_decode($saved_relative);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Person', $is_success_2->meta->errors);
				}

				$saved_relative_2 			= $this->dispatch(new Saving(new Person, [], $is_success_2->data->id, new Organisation, $attributes['organisation']['id']));
			
				$is_success_2b 				= json_decode($saved_relative_2);
				if(!$is_success_2b->meta->success)
				{
					$errors->add('Person', $is_success_2b->meta->errors);
				}		
			
				if(isset($value['contacts']))
				{
					foreach ($value['contacts'] as $key2 => $value2) 
					{
						$contact['item']			= $value2['item'];
						$contact['value']			= $value2['value'];
						if($key==count($value['contacts'])-1)
						{
							$contact['is_default']	= true;
						}

						if(isset($value2['id']) && $value2['id']!='' && !is_null($value2['id']))
						{
							$contact['id']			= $value2['id'];
						}
						else
						{
							$contact['id']			= null;
						}

						$saved_contact 				= $this->dispatch(new Saving(new Contact, $contact, $contact['id'], new Person, $is_success_2->data->id));					
						$is_success_3 				= json_decode($saved_contact);
						if(!$is_success_3->meta->success)
						{
							$errors->add('Person', $is_success_3->meta->errors);
						}
					}
				}
			}
		}

		if(isset($attributes['contacts']))
		{
			foreach ($attributes['contacts'] as $key0 => $value0) 
			{
				foreach ($attributes['contacts'][$key0] as $key => $value) 
				{
					$contact					= $value;
					
					if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
					{
						$contact['id']			= $value['id'];
					}
					else
					{
						$contact['id']			= null;
					}

					$saved_contact 				= $this->dispatch(new Saving(new Contact, $contact, $contact['id'], new Person, $is_success->data->id));

					$is_success_2 				= json_decode($saved_contact);
					if(!$is_success_2->meta->success)
					{
						$errors->add('Person', $is_success_2->meta->errors);
					}
				}
			}
		}

		if(isset($attributes['schedules']))
		{
			foreach ($attributes['schedules'] as $key => $value) 
			{
				$schedule					= $value;
				
				if(isset($value['id']) && $value['id']!='' && !is_null($value['id']))
				{
					$schedule['id']			= $value['id'];
				}
				else
				{
					$schedule['id']			= null;
				}

				$saved_schedule 			= $this->dispatch(new Saving(new PersonSchedule, $schedule, $schedule['id'], new Person, $is_success->data->id));

				$is_success_2 				= json_decode($saved_schedule);
				if(!$is_success_2->meta->success)
				{
					$errors->add('Person', $is_success_2->meta->errors);
				}
			}
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
