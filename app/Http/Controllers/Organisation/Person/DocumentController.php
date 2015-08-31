<?php namespace App\Http\Controllers\Organisation\Person;
use Input, Session, App, Paginator, Redirect, DB, Config, PDF;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Person;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\Document;

class DocumentController extends BaseController
{
	protected $controller_name = 'dokumen';

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$search['checkwork'] 					= true;
		$sort 									= ['name' => 'asc'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 				= Session::get('user.chartpath');
		}
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			return Redirect::back()->with('alert_warning', 'Data personalia tidak tersedia atau tidak terdaftar sebagai karyawan.');
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];
		
		$filter 									= [];

		if(Input::has('q'))
		{
			$filter['search']['documenttag']		= Input::get('q');
			$filter['active']['q']					= 'Cari Kategori "'.Input::get('q').'"';
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
						case 'documenttag':
							$active = 'Cari Kategori ';
							break;

						default:
							$active = 'Cari Kategori ';
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
						case 'created_at':
							$active = 'Urutkan Tanggal Pembuatan ';
							break;
						
						default:
							$active = 'Urutkan Tanggal Pembuatan ';
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

		$this->layout->page 						= view('pages.organisation.person.document.index');
		$this->layout->page->controller_name 		= $this->controller_name;
		$this->layout->page->data 					= $data;
		$this->layout->page->person 				= $person;

		$this->layout->page->filter 				= 	[
															['prefix' => 'search', 'key' => 'documenttag', 'value' => 'Cari Kategori', 'values' => $tags],
															['prefix' => 'sort', 'key' => 'created_at', 'value' => 'Urutkan Tanggal Pembuatan', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
														];
														
		$this->layout->page->filtered 				= $filter;
		$this->layout->page->default_filter  		= ['org_id' => $data['id'], 'person_id' => $person['id']];

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		unset($search);
		unset($sort);

		// ---------------------- GENERATE CONTENT ----------------------
		if(Input::has('doc_id') && !is_null($id))
		{
			$doc_id 								= Input::get('doc_id');
			$search['id'] 							= $id;
			$search['personid'] 					= $person_id;
			$search['withattributes'] 				= ['details'];
			$sort 									= [];
			$results 								= $this->dispatch(new Getting(new PersonDocument, $search, $sort , 1, 1));
			$contents 								= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$persondocument 						= json_decode(json_encode($contents->data), true);

		}
		else
		{
			$persondocument 						= null;
		}
		
		$this->layout->pages 					= view('pages.organisation.person.document.create', compact('id', 'data', 'person', 'persondocument'));

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

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('doc_id'))
		{
			$doc_id 							= Input::get('doc_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $person_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		unset($search);
		unset($sort);

		$search['id'] 							= $doc_id;
		$search['organisationid'] 				= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= ['document_id' => $doc_id];

		$errors 								= new MessageBag();

		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new PersonDocument, $attributes, $id, new Person, $person_id));
		$is_success 							= json_decode($content);
		
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Person', $value2);
					}
				}
				else
				{
					$errors->add('Person', $value);
				}
			}
		}

		if(Input::has('template_id'))
		{
			$template_ids 						= Input::get('template_id');
			$detail_ids 						= Input::get('detail_id');
			$contents 							= Input::get('content');
			foreach ($template_ids as $key => $value) 
			{
				$attributes_2 					= ['template_id' => $value];
				$checkdate						= strtotime($contents[$key]);
				$checknumeric					= int($contents[$key]);
				$checkstring					= strlen($contents[$key]);
				if($checkdate)
				{
					$attributes_2['on']			= $contents[$key];
				}
				elseif($checknumeric)
				{
					$attributes_2['numeric']	= $contents[$key];
				}
				elseif($checkstring <= 255)
				{
					$attributes_2['string']		= $contents[$key];
				}
				else
				{
					$attributes_2['text']		= $contents[$key];
				}

				if(isset($detail_ids[$key]) && $detail_ids[$key]!='' && !is_null($detail_ids[$key]))
				{
					$attributes_2['id']			= $detail_ids[$key];
				}
				else
				{
					$attributes_2['id']			= null;
				}

				$saved_detail 					= $this->dispatch(new Saving(new DocumentDetail, $attributes_2, $attributes_2['id'], new PersonDocument, $is_success->data->id));
				$is_success_2 					= json_decode($saved_detail);

				if(!$is_success_2->meta->success)
				{
					foreach ($is_success_2->meta->errors as $key => $value) 
					{
						if(is_array($value))
						{
							foreach ($value as $key2 => $value2) 
							{
								$errors->add('Person', $value2);
							}
						}
						else
						{
							$errors->add('Person', $value);
						}
					}
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.person.documents.index', ['person_id' => $person_id, 'org_id' => $org_id])->with('alert_success', 'Dokumen sudah disimpan');
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

		if(Input::has('person_id'))
		{
			$person_id 					= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search 						= ['id' => $id, 'organisationid' => $org_id, 'personid' => $person_id, 'withattributes' => ['document.organisation', 'document', 'details', 'details.template']];
		$results 						= $this->dispatch(new Getting(new PersonDocument, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$persondocument 				= json_decode(json_encode($contents->data), true);
		
		unset($search);
		unset($sort);
		$search 						= ['id' => $person_id, 'organisationid' => $org_id, 'currentwork' => true];
		$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
		$contents 						= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 						= json_decode(json_encode($contents->data), true);

		$data 							= $persondocument['document']['organisation'];
		$document 						= $persondocument['document'];

		$template						= $persondocument['document']['template'];
		$template						= str_replace("//name//", $person['name'], $template);
		$template						= str_replace("//position//", ($person['works'] ? $person['works'][0]['name'] : ''), $template);
		
		foreach ($persondocument['details'] as $key => $value) 
		{
			$template					= str_replace("//".strtolower($value['template']['field'])."//", ($value['numeric'] ? $value['numeric'] : $value['text']), $template);
		}

		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->pages 					= view('pages.organisation.person.document.show', compact('id'));
		$this->layout->pages->data 				= $data;
		$this->layout->pages->person 			= $person;
		$this->layout->pages->document 			= $document;
		$this->layout->pages->template 			= $template;
		$this->layout->pages->persondocument 	= $persondocument;
		// dd($data);

		if(Input::has('pdf'))
		{
			$pdf = PDF::loadView('widgets.common.persondocument.print_pdf', ['data' => $data, 'document' => $persondocument, 'template' => $template])->setPaper('a4');

			return $pdf->stream();
			
		}

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

			if(Input::has('person_id'))
			{
				$person_id 					= Input::get('person_id');
			}
			else
			{
				App::abort(404);
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			$search 						= ['organisationid' => $org_id, 'id' => $person_id];
			$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$search 						= ['id' => $id, 'personid' => $person_id];
			$results 						= $this->dispatch(new Getting(new PersonDocument, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new PersonDocument, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.person.documents.index', ['org_id' => $org_id, 'person_id' => $person_id])->with('alert_success', 'Dokumen sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}