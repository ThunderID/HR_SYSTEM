<?php namespace App\Http\Controllers\Organisation\Document;
use Input, Session, App, Paginator, Redirect, DB, Config, Response, DateTime, DateInterval, DatePeriod;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Document;
use App\Models\Template;
use App\Models\Person;

class TemplateController extends BaseController
{
	protected $controller_name = 'template';

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

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		if(Input::has('doc_id'))
		{
			$id 								= Input::get('doc_id');
		}
		else
		{
			App::abort(404);
		}

		$search 								= ['id' => $id, 'organisationid' => $org_id, 'withattributes' => ['organisation']];
		$results 								= $this->dispatch(new Getting(new Document, $search, [] , 1, 1));
		$contents 								= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$document 								= json_decode(json_encode($contents->data), true);
		$data 									= $document['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.document.show', compact('id', 'data', 'document'));
		$this->layout->pages->data 				= $data;
		$this->layout->pages->document 			= $document;
		$this->layout->pages->route_back 		= route('hr.documents.index', ['org_id' => $org_id]);

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

		if(Input::has('doc_id'))
		{
			$doc_id 							= Input::get('doc_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $doc_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$document 								= json_decode(json_encode($contents->data), true);
		$data 									= $document['organisation'];

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.document.template.create', compact('id', 'data', 'document'));

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

		if(Input::has('doc_id'))
		{
			$doc_id 							= Input::get('doc_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id'] 							= $doc_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('field', 'type');

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Template, $attributes, $id, new Document, $doc_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Document', $value2);
					}
				}
				else
				{
					$errors->add('Document', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.documents.show', [$doc_id, 'org_id' => $org_id])->with('alert_success', 'Jadwal kalender "' . $contents->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}


	public function edit($id)
	{
		return $this->create($id);
	}

	public function destroy($id)
	{
		$attributes 						= ['email' => Config::get('user.email'), 'password' => Input::get('password')];

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
				$org_id 					= Session::get('user.organisation');
			}

			if(Input::has('doc_id'))
			{
				$doc_id 					= Input::get('doc_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Config::get('user.orgids')))
			{
				App::abort(404);
			}

			$search 						= ['organisationid' => $org_id, 'id' => $doc_id];
			$results 						= $this->dispatch(new Getting(new Document, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'documentid' => $doc_id];
			$results 						= $this->dispatch(new Getting(new Template, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Template, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.document.templates.index', ['org_id' => $org_id, 'doc_id' => $document_id])->with('local_msg', $errors)->with('alert_success', 'Cabang "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}