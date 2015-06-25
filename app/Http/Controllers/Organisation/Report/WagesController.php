<?php namespace App\Http\Controllers\Organisation\Report;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image;
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
use App\Models\PersonSchedule;

class WagesController extends BaseController 
{

	protected $controller_name 						= 'wages';

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

		if(!in_array($org_id, Config::get('user.orgids')))
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

		if(!in_array($org_id, Config::get('user.orgids')))
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
		$attributes['date_of_birth'] 			= date('Y-m-d', strtotime($attributes['date_of_birth']));

		if (Input::hasFile('link_profile_picture'))
		{
			$upload 							= $this->upload_image(Input::file('link_profile_picture'));			
			$attributes['avatar']				= $upload['ori'];
		}
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
			$org_id 							= Session::get('user.organisation');
		}

		if(Input::has('person_id'))
		{
			$id 								= Input::get('person_id');
		}

		if(!in_array($org_id, Config::get('user.orgids')))
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
		$this->layout->page 					= view('pages.person.show');
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

			if(!in_array($org_id, Config::get('user.orgids')))
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
				return Redirect::route('hr.persons.index', ['org_id' => $org_id])->with('local_msg', $errors)->with('alert_success', 'Data Personalia "' . $contents->data->name. '" sudah dihapus');
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
