<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\IPWhitelist;
use App\Models\GroupMenu;
use App\Models\Menu;
use App\Models\Person;

class IPWhitelistController extends BaseController 
{

	protected $controller_name 					= 'ipwhitelist';

	public function index()
	{
		$this->layout->page 					= view('pages.ipwhitelist.index');

		return $this->layout;
	}

	public function create($id = null)
	{
		if(is_null($id))
		{
			$data 				 				= null;
		}
		else
		{
			$result								= $this->dispatch(new Getting(new IPWhitelist, ['ID' => $id], ['id' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}
		
			$data 				 				= json_decode(json_encode($content->data), true);
		}

		$this->layout->page 					= view('pages.ipwhitelist.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		$attributes 							= Input::only('ip');
		$person_id 								= Session::get('loggedUser');
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new IPWhitelist, $attributes, $id));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('ipwhitelist', $value2);
					}
				}
				else
				{
					$errors->add('ipwhitelist', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();

			return Redirect::route('hr.ipwhitelists.show', [$is_success->data->id])->with('local_msg', $errors)->with('alert_success', 'IP "' . $is_success->data->ip. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
	{
		return Redirect::route('hr.ipwhitelists.index');
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
			$results 						= $this->dispatch(new Deleting(new IPWhitelist, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.ipwhitelists.index')->with('alert_success', 'IP "' . $contents->data->id. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}
