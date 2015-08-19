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
use App\Models\Work;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\Contact;
use App\Models\Branch;
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
				$filter['search']['chartnotadmin'] 		= true;
			}
		
			$filter['search']['checkwork'] 			= true;
		}
		else
		{
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
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		$errors 								= new MessageBag();


		DB::beginTransaction();

		if(Input::has('import'))
		{
			if (Input::hasFile('file_csv')) 
			{
				$file_csv 		= Input::file('file_csv');
				$attributes = [];				
				$sheet = Excel::load($file_csv)->toArray();				
				return $this->importcsv($sheet, $org_id);
			}
		}
		else
		{
			if(Input::has('id'))
			{
				$id 								= Input::get('id');
			}

			$attributes 							= Input::only('uniqid', 'username', 'name', 'prefix_title', 'suffix_title', 'gender', 'place_of_birth');
			
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
				$attributes['last_password_updated_at']	= date('Y-m-d');
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

	private function importcsv($sheet, $org_id)
	{
		DB::beginTransaction();

		$prev_org_code 							= 0;
		foreach($sheet as $i => $row)
		{
			if($prev_org_code!=$row['kodeorganisasi'])
			{
				unset($search);
				unset($sort);

				$search['code']					= $row['kodeorganisasi'];
				$sort 							= ['created_at' => 'asc'];
				$results 						= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
				$contents 						= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$organisation 					= json_decode(json_encode($contents->data), true);
				$org_id_code 					= $organisation['id'];
				$prev_org_code 					= $row['kodeorganisasi'];
			}

			$attributes[$i]['uniqid']			= $row['nik'];
			$attributes[$i]['prefix_title']		= $row['prefixtitle'];
			$attributes[$i]['name']				= $row['namalengkap'];
			$attributes[$i]['suffix_title']		= $row['suffixtitle'];
			$attributes[$i]['username']			= date('Ymdhis').'.org.'.rand(0,12000000).$org_id;
			$attributes[$i]['place_of_birth']	= $row['tempatlahir'];
			$attributes[$i]['date_of_birth']	= date('Y-m-d', strtotime($row['tanggallahir']));
			$attributes[$i]['gender']			= $row['gender']=='L' ? 'male' : 'female';
			$attributes[$i]['org_id']			= $org_id_code;

			$content 							= $this->dispatch(new Saving(new Person, $attributes[$i], null, new Organisation, $org_id_code));

			$is_success 						= json_decode($content);
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
			
			//save contact
			if(!$errors->count())
			{
				if($row['email']!='')
				{
					$email[$i]['item']				= 'email';
					$email[$i]['value']				= $row['email'];
					$email[$i]['is_default']		= true;

					$content 						= $this->dispatch(new Saving(new Contact, $email[$i], null, new Person, $is_success->data->id));

					$is_mail_success 				= json_decode($content);
					if(!$is_mail_success->meta->success)
					{
						foreach ($is_mail_success->meta->errors as $key => $value) 
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

				if($row['phone']!='')
				{
					$phone[$i]['item']				= 'phone';
					$phone[$i]['value']				= $row['phone'];
					$phone[$i]['is_default']		= true;

					$content 						= $this->dispatch(new Saving(new Contact, $phone[$i], null, new Person, $is_success->data->id));

					$is_phone_success 				= json_decode($content);
					if(!$is_phone_success->meta->success)
					{
						foreach ($is_phone_success->meta->errors as $key => $value) 
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


				if($row['alamat']!='')
				{
					$address[$i]['item']			= 'address';
					$address[$i]['value']			= $row['alamat'];
					$address[$i]['is_default']		= true;

					$content 						= $this->dispatch(new Saving(new Contact, $address[$i], null, new Person, $is_success->data->id));

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
			}

			//save marital statuses
			if(!$errors->count())
			{
				$marital[$i]['status']			= $row['statuskawin'];
				$marital[$i]['on']				= date('Y-m-d H:i:s');

				$content 						= $this->dispatch(new Saving(new MaritalStatus, $marital[$i], null, new Person, $is_success->data->id));

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

			//save ktp
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= 'KTP';
				$search['tag']							= 'Identity';
				$search['withattributes']				= ['templates'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$document 								= json_decode(json_encode($contents->data), true);

				$ktp[$i]['document_id']					= $document['id'];

				$content 								= $this->dispatch(new Saving(new PersonDocument, $ktp[$i], null, new Person, $is_success->data->id));
				$is_ktp_success 						= json_decode($content);
				
				if(!$is_ktp_success->meta->success)
				{
					foreach ($is_ktp_success->meta->errors as $key => $value) 
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

				if(!$errors->count())
				{
					foreach ($document['templates'] as $key => $value) 
					{
						switch($key)
						{
							case '0': 
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['string']		= $row['nomorktp'];
								break;
							case '1':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['text']		= $row['alamat'];
								break;
							case '2':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['string']		= $row['kota'];
								break;
							case '3':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['string']		= $row['agama'];
								break;
							case '4':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['string']		= $row['statuskawin'];
								break;
							case '5':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['string']		= $row['kewarganegaraan'];
								break;
							case '6':
								$ktpdetail[$i][$key]['template_id']	= $value['id'];
								$ktpdetail[$i][$key]['on']			= $row['berlakuhingga'];
								break;
						}

						if(isset($ktpdetail[$i][$key]) && !$errors->count())
						{
							$content 								= $this->dispatch(new Saving(new DocumentDetail, $ktpdetail[$i][$key], null, new PersonDocument, $is_ktp_success->data->id));
							$is_ktpd_success 						= json_decode($content);
							
							if(!$is_ktpd_success->meta->success)
							{
								foreach ($is_ktpd_success->meta->errors as $key => $value) 
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
				}
			}

			//save bank account
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= 'Akun Bank';
				$search['tag']							= 'Akun';
				$search['withattributes']				= ['templates'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$document 								= json_decode(json_encode($contents->data), true);

				$bank[$i]['document_id']				= $document['id'];

				$content 								= $this->dispatch(new Saving(new PersonDocument, $bank[$i], null, new Person, $is_success->data->id));
				$is_bank_success 						= json_decode($content);
				
				if(!$is_bank_success->meta->success)
				{
					foreach ($is_bank_success->meta->errors as $key => $value) 
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

				if(!$errors->count())
				{
					foreach ($document['templates'] as $key => $value) 
					{
						switch($key)
						{
							case '0': 
								$bankdetail[$i][$key]['template_id']	= $value['id'];
								$bankdetail[$i][$key]['string']			= $row['nomorrekening'];
								break;
							case '1':
								$bankdetail[$i][$key]['template_id']	= $value['id'];
								$bankdetail[$i][$key]['string']			= $row['namabank'];
								break;
							case '2':
								$bankdetail[$i][$key]['template_id']	= $value['id'];
								$bankdetail[$i][$key]['string']			= $row['namanasabah'];
								break;
						}

						if(isset($bankdetail[$i][$key]) && !$errors->count())
						{
							$content 								= $this->dispatch(new Saving(new DocumentDetail, $bankdetail[$i][$key], null, new PersonDocument, $is_bank_success->data->id));
							$is_bankd_success 						= json_decode($content);
							
							if(!$is_bankd_success->meta->success)
							{
								foreach ($is_bankd_success->meta->errors as $key => $value) 
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
				}
			}

			//save npwp
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= 'NPWP';
				$search['tag']							= 'Pajak';
				$search['withattributes']				= ['templates'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$document 								= json_decode(json_encode($contents->data), true);

				$npwp[$i]['document_id']				= $document['id'];

				$content 								= $this->dispatch(new Saving(new PersonDocument, $npwp[$i], null, new Person, $is_success->data->id));
				$is_npwp_success 						= json_decode($content);
				
				if(!$is_npwp_success->meta->success)
				{
					foreach ($is_npwp_success->meta->errors as $key => $value) 
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

				if(!$errors->count())
				{
					foreach ($document['templates'] as $key => $value) 
					{
						switch($key)
						{
							case '0': 
								$npwpdetail[$i][$key]['template_id']	= $value['id'];
								$npwpdetail[$i][$key]['string']			= $row['npwp'];
								break;
						}

						if(isset($npwpdetail[$i][$key]) && !$errors->count())
						{
							$content 								= $this->dispatch(new Saving(new DocumentDetail, $npwpdetail[$i][$key], null, new PersonDocument, $is_npwp_success->data->id));
							$is_npwpd_success 						= json_decode($content);
							
							if(!$is_npwpd_success->meta->success)
							{
								foreach ($is_npwpd_success->meta->errors as $key => $value) 
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
				}
			}

			//save bpjstk
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= 'BPJS Ketenagakerjaan';
				$search['tag']							= 'Pajak';
				$search['withattributes']				= ['templates'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$document 								= json_decode(json_encode($contents->data), true);

				$bpjstk[$i]['document_id']				= $document['id'];

				$content 								= $this->dispatch(new Saving(new PersonDocument, $bpjstk[$i], null, new Person, $is_success->data->id));
				$is_bpjstk_success 						= json_decode($content);
				
				if(!$is_bpjstk_success->meta->success)
				{
					foreach ($is_bpjstk_success->meta->errors as $key => $value) 
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

				if(!$errors->count())
				{
					foreach ($document['templates'] as $key => $value) 
					{
						switch($key)
						{
							case '0': 
								$bpjstkdetail[$i][$key]['template_id']	= $value['id'];
								$bpjstkdetail[$i][$key]['string']		= $row['bpjsketenagakerjaan'];
								break;
						}

						if(isset($bpjstkdetail[$i][$key]) && !$errors->count())
						{
							$content 								= $this->dispatch(new Saving(new DocumentDetail, $bpjstkdetail[$i][$key], null, new PersonDocument, $is_bpjstk_success->data->id));
							$is_bpjstkd_success 						= json_decode($content);
							
							if(!$is_bpjstkd_success->meta->success)
							{
								foreach ($is_bpjstkd_success->meta->errors as $key => $value) 
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
				}
			}

			//save bpjs kesehatan
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= 'BPJS Kesehatan';
				$search['tag']							= 'Pajak';
				$search['withattributes']				= ['templates'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					App::abort(404);
				}

				$document 								= json_decode(json_encode($contents->data), true);

				$bpjsk[$i]['document_id']				= $document['id'];

				$content 								= $this->dispatch(new Saving(new PersonDocument, $bpjsk[$i], null, new Person, $is_success->data->id));
				$is_bpjsk_success 						= json_decode($content);
				
				if(!$is_bpjsk_success->meta->success)
				{
					foreach ($is_bpjsk_success->meta->errors as $key => $value) 
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

				if(!$errors->count())
				{
					foreach ($document['templates'] as $key => $value) 
					{
						switch($key)
						{
							case '0': 
								$bpjskdetail[$i][$key]['template_id']	= $value['id'];
								$bpjskdetail[$i][$key]['string']		= $row['bpjskesehatan'];
								break;
						}

						if(isset($bpjskdetail[$i][$key]) && !$errors->count())
						{
							$content 								= $this->dispatch(new Saving(new DocumentDetail, $bpjskdetail[$i][$key], null, new PersonDocument, $is_bpjsk_success->data->id));
							$is_bpjskd_success 						= json_decode($content);
							
							if(!$is_bpjskd_success->meta->success)
							{
								foreach ($is_bpjskd_success->meta->errors as $key => $value) 
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
				}
			}

			//save kalender
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['starthour']					= date('H:i:s', strtotime($row['jammasukkerja']));
				$search['endhour']						= date('H:i:s', strtotime($row['jampulangkerja']));
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$calendar[$i]['import_from_id'] 	= 1;
					$calendar[$i]['name'] 				= 'Costum Kalender Untuk'.$row['namacabang'];
					$calendar[$i]['workdays'] 			= 'senin,selasa,rabu,kamis,jumat';
					$calendar[$i]['start'] 				= date('H:i:s', strtotime($row['jammasukkerja']));
					$calendar[$i]['end'] 				= date('H:i:s', strtotime($row['jampulangkerja']));

					$content 							= $this->dispatch(new Saving(new Calendar, $calendar[$i], null, new Organisation, $org_id_code));

					$is_calendar_success 				= json_decode($content);
					if(!$is_calendar_success->meta->success)
					{
						foreach ($is_calendar_success->meta->errors as $key => $value) 
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
				else
				{
					$is_calendar_success 				= $contents->data;
				}
			}

			//save branch
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['name']							= $row['namacabang'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$branch[$i]['name'] 				= $row['namacabang'];

					$content 							= $this->dispatch(new Saving(new Branch, $branch[$i], null, new Organisation, $org_id_code));

					$is_branch_success 					= json_decode($content);
					if(!$is_branch_success->meta->success)
					{
						foreach ($is_branch_success->meta->errors as $key => $value) 
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
				else
				{
					$is_branch_success 					= $contents->data;
				}
			}

			//save Chart
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['organisationid']				= $org_id_code;
				$search['branchid']						= $is_branch_success->id;
				$search['tag']							= $row['namadepartemen'];
				$search['name']							= $row['jabatan'];
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$chart[$i]['name'] 					= $row['jabatan'];
					$chart[$i]['tag'] 					= $row['namadepartemen'];
					$chart[$i]['min_employee'] 			= 10;
					$chart[$i]['ideal_employee'] 		= 10;
					$chart[$i]['max_employee'] 			= 10;

					$content 							= $this->dispatch(new Saving(new Chart, $chart[$i], null, new Branch, $is_branch_success->id));

					$is_chart_success 					= json_decode($content);
					if(!$is_chart_success->meta->success)
					{
						foreach ($is_chart_success->meta->errors as $key => $value) 
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
				else
				{
					$is_chart_success 					= $contents->data;
				}
			}

			//save Follow
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['chartid']						= $is_chart_success->id;
				$search['calendarid']					= $is_calendar_success->id;
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Follow, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$follow[$i]['chart_id'] 			= $is_chart_success->id;

					$content 							= $this->dispatch(new Saving(new Follow, $follow[$i], null, new Calendar, $is_calendar_success->id));

					$is_follow_success 					= json_decode($content);
					if(!$is_follow_success->meta->success)
					{
						foreach ($is_follow_success->meta->errors as $key => $value) 
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

			//Check Cuti 
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['quota']						= $row['kuotacutitahunan'];
				$search['status']						= 'CN';
				$search['active']						= true;
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$workleave[$i]['name'] 				= 'Custom Cuti '.$row['namacabang'];
					$workleave[$i]['quota'] 			= $row['kuotacutitahunan'];
					$workleave[$i]['status'] 			= 'CN';
					$workleave[$i]['is_active'] 		= true;

					$content 							= $this->dispatch(new Saving(new Workleave, $workleave[$i], null, new Organisation, $org_id_code));

					$is_workleave_success 				= json_decode($content);
					if(!$is_workleave_success->meta->success)
					{
						foreach ($is_workleave_success->meta->errors as $key => $value) 
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
				else
				{
					$is_workleave_success 				= $contents->data;
				}
			}

			//save Work
			if(!$errors->count())
			{
				unset($search);
				unset($sort);

				$search['chartid']						= $is_chart_success->id;
				$search['calendarid']					= $is_calendar_success->id;
				$search['personid']						= $is_success->id;
				$search['status']						= $row['statuskerja'];
				
				$sort 									= ['created_at' => 'asc'];
				$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
				$contents 								= json_decode($results);		

				if(!$contents->meta->success)
				{
					$work[$i]['chart_id'] 				= $is_chart_success->id;
					$work[$i]['calendar_id'] 			= $is_calendar_success->id;
					$work[$i]['status'] 				= $is_chart_success->id;
					switch (strtolower($row['statuskerja'])) 
					{
						case 'kontrak': case 'contract' :
							$work[$i]['start'] 			= 'contract';
							break;						
						case 'tetap': case 'permanent' : case 'permanen' :
							$work[$i]['start'] 			= 'permanent';
							break;
						case 'percobaan': case 'probation' :
							$work[$i]['start'] 			= 'probation';
							break;
						case 'magang': case 'internship' : case 'training' :
							$work[$i]['start'] 			= 'training';
							break;
						default:
							$work[$i]['start'] 			= 'others';
							break;
					}
					if($row['berhentikerja']!='')
					{
						$work[$i]['end'] 				= date('Y-m-d', strtotime($row['berhentikerja']));
						$work[$i]['reason_end_job'] 	= 'Akhir '.$row['statuskerja'];
					}

					$content 							= $this->dispatch(new Saving(new Work, $work[$i], null, new Person, $is_success->id));

					$is_work_success 					= json_decode($content);
					if(!$is_work_success->meta->success)
					{
						foreach ($is_work_success->meta->errors as $key => $value) 
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

			//save FolloWorkleve
			if(!$errors->count())
			{
				$fwleave[$i]['work_id'] 		= $is_work_success->id;

				$content 						= $this->dispatch(new Saving(new FollowWorkleve, $fwleave[$i], null, new Workleave, $is_workleave_success->id));

				$is_fwleave_success 			= json_decode($content);
				if(!$is_fwleave_success->meta->success)
				{
					foreach ($is_fwleave_success->meta->errors as $key => $value) 
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

			//save Pemberian Cuti
			if(!$errors->count())
			{
				if(in_array($is_work_success->status, ['contract', 'permanent']))
				{
					$personworkleave[$i]['work_id'] 		= $is_work_success->id;
					$personworkleave[$i]['workleave_id'] 	= $is_workleave_success->id;
					$personworkleave[$i]['created_by'] 		= Session::get('loggedUser');
					$personworkleave[$i]['name'] 			= 'Pemberian (awal) '.$is_workleave_success->name;
					$personworkleave[$i]['notes'] 			= 'Auto generated dari import csv.';
					$personworkleave[$i]['start'] 			= date('Y-m-d', strtotime('first day of January this year'));
					$personworkleave[$i]['end'] 			= date('Y-m-d', strtotime('last day of December this year'));
					$personworkleave[$i]['quota'] 			= $is_workleave->quota;
					$personworkleave[$i]['status'] 			= 'CN';

					$content 								= $this->dispatch(new Saving(new PersonWorkleave, $personworkleave[$i], null, new Person, $is_success->id));

					$is_personworkleave_success 			= json_decode($content);
					if(!$is_personworkleave_success->meta->success)
					{
						foreach ($is_personworkleave_success->meta->errors as $key => $value) 
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

			//save Sisa Cuti
			if(!$errors->count())
			{
				$pwleave[$i]['work_id'] 				= $is_work_success->id;
				if(isset($is_personworkleave_success->id))
				{
					$pwleave[$i]['person_workleave_id'] = $is_personworkleave_success->id;
				}
				$pwleave[$i]['created_by'] 				= Session::get('loggedUser');
				$pwleave[$i]['name'] 					= 'Pengambilan (awal) '.$is_workleave_success->name;
				$pwleave[$i]['notes'] 					= 'Auto generated dari import csv. Hanya Quota yang valid';
				
				$leave 									= $row['kuotacutitahunan'] - $row['sisacuti'];
				if($leave == 1)
				{
					$leaves 							= '';
				}
				elseif($leave==2)
				{
					$leaves 							= ' + 1 day';
				}
				else
				{
					$leaves 							= ' + '.$leave.' days';
				}

				$pwleave[$i]['start'] 					= date('Y-m-d', strtotime($row['mulaikerja']));
				$pwleave[$i]['end'] 					= date('Y-m-d', strtotime($row['mulaikerja'].$leaves));
				$pwleave[$i]['quota'] 					= 0 - $leave;
				$pwleave[$i]['status'] 					= 'CN';

				if($leave>0)
				{
					$content 							= $this->dispatch(new Saving(new PersonWorkleave, $pwleave[$i], null, new Person, $is_success->id));

					$is_pwleave_success 				= json_decode($content);
					if(!$is_pwleave_success->meta->success)
					{
						foreach ($is_pwleave_success->meta->errors as $key => $value) 
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
		}

		if(!$errors->count())
		{
			DB::commit();
			return Redirect::route('hr.persons.index', ['org_id' => $org_id])->with('alert_success', 'Data Import Sudah Disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors);
	}
}
