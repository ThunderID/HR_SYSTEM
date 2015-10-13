<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB, Config,Response, Excel, Validator;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Organisation;
use App\Models\Document;
use App\Models\Template;
use App\Models\Person;
use App\Models\Queue;

class DocumentController extends BaseController 
{

	protected $controller_name 						= 'dokumen';

	public function index()
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']								= $org_id;
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);

		$filter 									= [];

		if(Input::has('q'))
		{
			$filter['search']['name']				= Input::get('q');
			$filter['active']['q']					= 'Cari Nama "'.Input::get('q').'"';
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
						case 'name':
							$active = 'Cari Nama ';
							break;

						case 'tag':
							$active = 'Cari Kategori ';
							break;
						
						default:
							$active = 'Cari Nama ';
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
		$sort 										= ['tag' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Document, $search, $sort , 1, 100));
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

		$this->layout->page 						= view('pages.organisation.document.index', compact('data'));
		$this->layout->page->filter 				= 	[
															['prefix' => 'search', 'key' => 'tag', 'value' => 'Cari Kategori', 'values' => $tags],
															['prefix' => 'sort', 'key' => 'name', 'value' => 'Urutkan Nama', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
														];
		$this->layout->page->filtered 				= $filter;
		$this->layout->page->default_filter  		= ['org_id' => $data['id']];


		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']								= $org_id;
		$sort 										= ['name' => 'asc'];
		$results 									= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);
		$this->layout->page 						= view('pages.organisation.document.create', compact('data', 'id'));

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

		$errors 								= new MessageBag();
		
		if(Input::has('import'))
		{
			if (Input::hasFile('file_csv') && Input::has('doc_id')) 
			{
				$validator = Validator::make(['file' => Input::file('file_csv'), 
												'extension' => strtolower(Input::file('file_csv')->getClientOriginalExtension())], 
											['file' => 'required|max:20', 'extension' => 'required|in:csv']);
				
				if (!$validator->passes())
				{
					return Redirect::back()->withErrors($validator->errors())->withInput();
				}

				$file_csv 		= Input::file('file_csv');
				$attributes = [];				
				$sheet = Excel::load($file_csv)->toArray();				
				
				$queattr['created_by'] 					= Session::get('loggedUser');
				$queattr['process_name'] 				= 'hr:persondocumentbatch';
				$queattr['parameter'] 					= json_encode(['csv' => $sheet, 'doc_id' => Input::get('doc_id')]);
				$queattr['total_process'] 				= count($sheet);
				$queattr['task_per_process']			= 1;
				$queattr['process_number'] 				= 0;
				$queattr['total_task'] 					= count($sheet);
				$queattr['message'] 					= 'Initial Queue';

				$content 								= $this->dispatch(new Saving(new Queue, $queattr, null));
				$is_success_2 							= json_decode($content);

				if(!$is_success_2->meta->success)
				{
					foreach ($is_success_2->meta->errors as $key2 => $value2) 
					{
						if(is_array($value2))
						{
							foreach ($value2 as $key3 => $value3) 
							{
								$errors->add('Batch', $value3);
							}
						}
						else
						{
							$errors->add('Batch', $value2);
						}
					}
				}

				return Redirect::route('hr.persons.index', ['org_id' => $org_id])->with('alert_info', 'Data sedang disimpan');
			}
		}
		else
		{
			$attributes 							= Input::only('name', 'tag', 'template');
			
			if(!is_null($id))
			{
				$content 							= $this->dispatch(new Getting(new Document, ['id' => $id, 'withattributes' => ['templates']], [], 1, 1));

				$results 							= json_decode($content);
				if(!$results->meta->success)
				{
					foreach ($results->meta->errors as $key => $value) 
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

				$document 							= json_decode(json_encode($results->data), true);
				$templates 							= $document['templates'];
			}

			DB::beginTransaction();
			
			$content 								= $this->dispatch(new Saving(new Document, $attributes, $id, new Organisation, $org_id));

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

			$ids 									= [];

			if(Input::has('field'))
			{
				$fields 							= Input::get('field');
				$types 								= Input::get('type');
				$ids 								= Input::get('temp_id');
				
				foreach ($fields as $key => $value) 
				{
					if($value!='' && !is_null($value) && $types[$key]!='' && !is_null($types[$key]))
					{
						$template['field']				= $value;
						$template['type']				= $types[$key];

						if(isset($ids[$key]) && $ids[$key]!='' && !is_null($ids[$key]) && (int)$ids[$key])
						{
							$template['id']				= $ids[$key];
						}
						else
						{
							$template['id']				= null;
						}

						$saved_template 				= $this->dispatch(new Saving(new Template, $template, $template['id'], new Document, $is_success->data->id));
						$is_success_2 					= json_decode($saved_template);
						if(!$is_success_2->meta->success)
						{
							foreach ($is_success_2->meta->errors as $key => $value) 
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
					}
				}
			}

			if(isset($templates))
			{
				foreach ($templates as $key => $value) 
				{
					if(!in_array($value['id'], $ids))
					{
						$search 						= ['organisationid' => $org_id, 'id' => $id];
						$results 						= $this->dispatch(new Getting(new Document, $search, [] , 1, 1));
						$contents 						= json_decode($results);
						
						if(!$contents->meta->success)
						{
							foreach ($contents->meta->errors as $key => $value1) 
							{
								if(is_array($value1))
								{
									foreach ($value1 as $key2 => $value2) 
									{
										$errors->add('Document', $value2);
									}
								}
								else
								{
									$errors->add('Document', $value1);
								}
							}
						}

						$search 						= ['id' => $value['id'], 'documentid' => $id];
						$results 						= $this->dispatch(new Getting(new Template, $search, [] , 1, 1));
						$contents 						= json_decode($results);
						
						if(!$contents->meta->success)
						{
							foreach ($contents->meta->errors as $key => $value1) 
							{
								if(is_array($value1))
								{
									foreach ($value1 as $key2 => $value2) 
									{
										$errors->add('Document', $value2);
									}
								}
								else
								{
									$errors->add('Document', $value1);
								}
							}
						}

						$results 						= $this->dispatch(new Deleting(new Template, $value['id']));
						$contents 						= json_decode($results);

						if (!$contents->meta->success)
						{
							foreach ($contents->meta->errors as $key => $value1) 
							{
								if(is_array($value1))
								{
									foreach ($value1 as $key2 => $value2) 
									{
										$errors->add('Document', $value2);
									}
								}
								else
								{
									$errors->add('Document', $value1);
								}
							}
						}
					}
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.documents.show', [$is_success->data->id, 'org_id' => $org_id])->with('alert_success', 'Dokumen "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
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
		$this->layout->pages 					= view('pages.organisation.document.show', compact('id', 'data', 'document'));
		$this->layout->pages->data 				= $data;
		$this->layout->pages->document 			= $document;
		$this->layout->pages->route_back 		= route('hr.documents.index', ['org_id' => $org_id]);

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

			$search 						= ['id' => $id, 'organisationid' => $org_id];
			$results 						= $this->dispatch(new Getting(new Document, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Document, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.documents.index', ['org_id' => $org_id])->with('alert_success', 'Dokumen "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}

	public function ajax()
	{
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['organisationid']					= $org_id;
		$sort 										= ['name' => 'asc'];

		$results 									= $this->dispatch(new Getting(new Document, $search, [], 100, 100,false));
		$contents 									= json_decode($results);
		
		if(!$contents->meta->success)
		{
			return Response::json(['data' => 'data tidak ada'], 500);
		}

		$document 									= json_decode(json_encode($contents->data), true);

		return Response::json(['data' => $document], 200);
		
	}
}
