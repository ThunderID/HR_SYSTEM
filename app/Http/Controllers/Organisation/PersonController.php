<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image, Hash, Excel;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\MaritalStatus;
use App\Models\Contact;
use App\Models\Branch;
use App\Models\Queue;

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

		unset($search);
		unset($sort);

		$search 									= ['organisationid' => $org_id];
		if(Session::get('user.menuid')>=5)
		{
			$search['id'] 							= Session::get('user.branchid');
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		else
		{
			$search['withattributes'] 				= ['charts'];
		}
		$results 									= $this->dispatch(new Getting(new Branch, $search, [] , 1, 100));
		$contents 									= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$branch 									= json_decode(json_encode($contents->data), true);

		$tags 										= [];

		foreach ($branch as $key => $value) 
		{
			$branches[$key]['key'] 					= $value['id'];
			$branches[$key]['value'] 				= $value['name'];
			if(Input::has('key'))
			{
				foreach (Input::get('key') as $key2 => $value2) 
				{
					if(strtolower($value2)=='search_branchid' && (int)Input::get('value')[$key2]==(int)$value['id'])
					{
						$branchname 				= $value['name'];
					}
				}
			}
			if(isset($filter['search']['branchid']))
			{
				foreach ($value['charts'] as $key2 => $value2) 
				{
					if(!in_array($value2['tag'], $tags))
					{
						$charts[$key]['key'] 		= $value2['tag'];
						$charts[$key]['value'] 		= $value2['tag'];
						$tags[]						= $value2['tag'];
					}
				}
			}
		}

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

						case 'branchid':
							$active = 'Cari Cabang ';
							break;

						case 'charttag':
							$active = 'Cari Departemen ';
							break;

						case 'gender':
							$active = 'Cari Gender ';
							break;

						case 'checkwork':
							$active = 'Cari Karyawan ';
							break;

						default:
							$active = 'Cari Nama ';
							break;
					}

					switch (strtolower($filter_search)) 
					{
						//need to enhance to get branch name
						case 'branchid':
							if(isset($branchname))
							{
								$active = $active.'"'.$branchname.'"';
							}
							else
							{
								$active = $active.'"'.$dirty_filter_value[$key].'"';
							}
							break;
						case 'checkwork':
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
		
		if(!isset($filter['search']['checkwork']) || (isset($filter['search']['checkwork']) && $filter['search']['checkwork']==true))
		{
			if(Session::get('user.menuid')>=5)
			{
				$filter['search']['chartchild'] 		= Session::get('user.chartpath');
				$filter['active']['chartchild'] 		= 'Lihat Sebagai "'.Session::get('user.chartname').'"';
			}
			else
			{
				$filter['search']['chartnotadmin'] 		= date('Y-m-d');
			}
		
			$filter['search']['checkwork'] 			= true;
		}
		else
		{
			$filter['search']['checkwork'] 			= false;
			$filter['active']['checkwork'] 			= 'Cari Data Non Karyawan';
		}

		$this->layout->page 						= view('pages.organisation.person.index', compact('data'));
		$filters 									= 	[
															['prefix' => 'search', 'key' => 'checkwork', 'value' => 'Status', 'values' => [['key' => true, 'value' => 'Karyawan'], ['key' => false, 'value' => 'Non Karyawan']]],
															['prefix' => 'search', 'key' => 'gender', 'value' => 'Cari Gender', 'values' => [['key' => 'female', 'value' => 'Wanita'], ['key' => 'male', 'value' => 'Pria']]],
															['prefix' => 'sort', 'key' => 'name', 'value' => 'Urutkan Nama', 'values' => [['key' => 'asc', 'value' => 'A-Z'], ['key' => 'desc', 'value' => 'Z-A']]]
														];

		if(isset($branches))
		{
			$filters[] 					 			= ['prefix' => 'search', 'key' => 'branchid', 'value' => 'Cari Di Cabang', 'values' => $branches];
		}
		if(isset($charts))
		{
			$filters[] 					 			= ['prefix' => 'search', 'key' => 'charttag', 'value' => 'Cari Di Departemen', 'values' => $charts];
		}

		$this->layout->page->filter 				= $filters;
		$this->layout->page->filtered 				= $filter;

		$this->layout->page->default_filter  		= ['org_id' => $data['id']];

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

		$search['id']							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		$this->layout->page 					= view('pages.organisation.person.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		$errors 									= new MessageBag();
		if(Input::has('import'))
		{
			if (Input::hasFile('file_csv')) 
			{
				$validator = Validator::make(['file' => Input::file('file_csv'), 
												'extension' => strtolower(Input::file('file_csv')->getClientOriginalExtension())], 
											['file' => 'required|max:20', 'extension' => 'required|in:csv']);
				
				if (!$validator->passes())
				{
					return Redirect::back()->withErrors($validator->errors())->withInput();
				}

				$file_csv 		= Input::file('file_csv');
				$attributes 	= [];				
				$sheet 			= Excel::load($file_csv)->toArray();				
				
				$queattr['created_by'] 					= Session::get('loggedUser');
				$queattr['process_name'] 				= 'hr:personbatch';
				$queattr['parameter'] 					= json_encode($sheet);
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
			DB::beginTransaction();

			if(Input::has('id'))
			{
				$id 								= Input::get('id');
			}

			$attributes 							= Input::only('uniqid', 'name', 'prefix_title', 'suffix_title', 'gender', 'place_of_birth', 'username');
			
			if (Input::has('date_of_birth'))
			{
				$attributes['date_of_birth'] 		= date('Y-m-d', strtotime(Input::get('date_of_birth')));
			}

			if (Input::hasFile('link_profile_picture'))
			{
				$upload 							= $this->upload_image(Input::file('link_profile_picture'));			
				$attributes['avatar']				= $upload['ori'];
			}

			if(!in_array($org_id, Session::get('user.organisationids')))
			{
				App::abort(404);
			}

			if(Input::has('password'))
			{
				$attributes['password']					= Hash::make(Input::get('password'));
				$attributes['last_password_updated_at']	= date('Y-m-d H:i:s');
			}
			else
			{
				unset($attributes['last_password_updated_at']);
			}

			$content 								= $this->dispatch(new Saving(new Person, $attributes, $id, new Organisation, $org_id));

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

			if(!$errors->count() && Input::has('email'))
			{
				$email['item']						= 'email';
				$email['value']						= Input::get('email');
				$email['is_default']				= true;

				$content 							= $this->dispatch(new Saving(new Contact, $email, null, new Person, $is_success->data->id));

				$is_email_success 					= json_decode($content);
				if(!$is_email_success->meta->success)
				{
					foreach ($is_email_success->meta->errors as $key => $value) 
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

			if(!$errors->count() && Input::has('mobile'))
			{
				$mobile['item']						= 'mobile';
				$mobile['value']					= Input::get('mobile');
				$mobile['is_default']				= true;

				$content 							= $this->dispatch(new Saving(new Contact, $mobile, null, new Person, $is_success->data->id));

				$is_mobile_success 					= json_decode($content);
				if(!$is_mobile_success->meta->success)
				{
					foreach ($is_mobile_success->meta->errors as $key => $value) 
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

			if(!$errors->count() && Input::has('address'))
			{
				$address['item']					= 'address';
				$address['value']					= Input::get('address');
				$address['is_default']				= true;

				$content 							= $this->dispatch(new Saving(new Contact, $address, null, new Person, $is_success->data->id));

				$is_address_success 				= json_decode($content);
				if(!$is_address_success->meta->success)
				{
					foreach ($is_address_success->meta->errors as $key => $value) 
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

			if(!$errors->count() && Input::has('marital'))
			{
				$marital['status']					= Input::get('marital');
				$marital['on']						= date('Y-m-d H:i:s');

				$content 							= $this->dispatch(new Saving(new MaritalStatus, $marital, null, new Person, $is_success->data->id));

				$is_marital_success 				= json_decode($content);
				if(!$is_marital_success->meta->success)
				{
					foreach ($is_marital_success->meta->errors as $key => $value) 
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

			if(!$errors->count() && Input::has('document_id'))
			{
				$docs 								= Input::get('document_id');
				$tmps 								= Input::get('template_id');
				$cnts 								= Input::get('content');

				foreach ($docs as $key => $value) 
				{
					$doc['document_id']				= $value;

					$content 						= $this->dispatch(new Saving(new PersonDocument, $doc, null, new Person, $is_success->data->id));

					$is_document_success 			= json_decode($content);
					if(!$is_document_success->meta->success)
					{
						foreach ($is_document_success->meta->errors as $key2 => $value2) 
						{
							if(is_array($value2))
							{
								foreach ($value2 as $key3 => $value3) 
								{
									$errors->add('Person', $value3);
								}
							}
							else
							{
								$errors->add('Person', $value2);
							}
						}
					}
					else
					{
						foreach ($tmps[$key] as $key4 => $value4) 
						{
							$dtl['template_id']				= $value4;
							$val 							= explode('-', $cnts[$key][$key4]);
							if(count($val)==3)
							{
								$dtl['on']					= date('Y-m-d H:i:s', strtotime($val[2].'-'.$val[1].'-'.$val[0]));
							}
							elseif((int)$cnts[$key][$key4])
							{
								$dtl['numeric']				= $cnts[$key][$key4];
							}
							elseif(strlen($cnts[$key][$key4]) <= 255)
							{
								$dtl['string']				= $cnts[$key][$key4];
							}
							else
							{
								$dtl['text']				= $cnts[$key][$key4];
							}

							$content 						= $this->dispatch(new Saving(new DocumentDetail, $dtl, null, new PersonDocument, $is_document_success->data->id));

							$is_detail_success 				= json_decode($content);
							if(!$is_detail_success->meta->success)
							{
								foreach ($is_detail_success->meta->errors as $key5 => $value5) 
								{
									if(is_array($value5))
									{
										foreach ($value5 as $key6 => $value6) 
										{
											$errors->add('Person', $value6);
										}
									}
									else
									{
										$errors->add('Person', $value5);
									}
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
			return Redirect::route('hr.persons.show', [$is_success->data->id, 'org_id' => $org_id])->with('alert_success', 'Data "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id)
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
			$id 								= Input::get('person_id');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id'] 							= $id;
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
		$this->layout->page 					= view('pages.organisation.person.show');
		$this->layout->page->controller_name 	= $this->controller_name;
		$this->layout->page->data 				= $data;
		$this->layout->page->person 			= $person;
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
			$results 						= $this->dispatch(new Getting(new Person, $search, [] , 1, 1));
			$contents 						= json_decode($results);
			
			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Person, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.persons.index', ['org_id' => $org_id])->with('alert_success', 'Data Personalia "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}

	public function upload_image($file)
	{		
		if ($file)
		{
			$validator = Validator::make(['file' => $file], ['file' => 'image|max:500']);
			if (!$validator->passes())
			{
				return Response::json(['message' => $validator->errors()->first()], 500);
			}

			// generate path 
			$path = '/images/' . date('Y/m/d/H') . '/'; 

			// generate filename
			$filename =  str_replace(' ', '-', $file->getClientOriginalName());

			$i = 1;
			while (file_exists(public_path() . '/' . $path . $filename))
			{
				$filename = $i . '-' . str_replace(' ', '-', $file->getClientOriginalName());
				$i++;
			}
			
			// move uploaded file to path
			$file->move(public_path() . '/' . $path,  str_replace(' ', '-', $filename));

			// create 
			$paths['sm'] = asset($this->copyAndResizeImage($path . $filename, 320, 180));
			$paths['md'] = asset($this->copyAndResizeImage($path . $filename, 640, 360));
			$paths['lg'] = asset($this->copyAndResizeImage($path . $filename, 960, 540));
			$paths['ori'] = asset($path .  $filename);			
		}

		return $paths;
	}

	private function copyAndResizeImage($image_path, $width, $height)
	{
		if (!is_integer($width))
		{
			throw new InvalidArgumentException("Width must be type of integer", 1);
		}

		if (!is_integer($height))
		{
			throw new InvalidArgumentException("Height must be type of integer", 1);
		}

		//
		$path 			= pathinfo($image_path);
		$new_file_name 	= $path['filename'] . '_' . $width . 'x' . $height . '.' . $path['extension'];
		// process
		$image = Image::make(public_path() . '/' . $image_path)->resize($width, $height)->save(public_path($path['dirname'] . '/' . $new_file_name));

		return $path['dirname'] . '/' . $new_file_name;
	}
}
