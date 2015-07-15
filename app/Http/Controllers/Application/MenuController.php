<?php namespace App\Http\Controllers\Application;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\Menu;
use App\Models\Work;
use App\Models\Person;

class MenuController extends BaseController 
{

	protected $controller_name 					= 'menu';

	public function index()
	{		
		if(Input::has('app_id'))
		{
			$app_id 							= Input::get('app_id');
		}
		else
		{
			App::abort(404);
		}

		$result									= $this->dispatch(new Getting(new Application, ['ID' => $app_id], ['created_at' => 'asc'] ,1, 1));

		$content 								= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		$data 				 					= json_decode(json_encode($content->data), true);

		$this->layout->page 					= view('pages.menu.index', compact('data'));

		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('app_id'))
		{
			$app_id 							= Input::get('app_id');
		}
		else
		{
			App::abort(404);
		}

		$result									= $this->dispatch(new Getting(new Application, ['ID' => $app_id], ['created_at' => 'asc'] ,1, 1));

		$content 								= json_decode($result);

		$data 				 					= json_decode(json_encode($content->data), true);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		if(is_null($id))
		{
			$this->layout->page 				= view('pages.menu.create', compact('id', 'data'));
		}
		else
		{
			$result								= $this->dispatch(new Getting(new Menu, ['ID' => $id, 'applicationid' => $app_id], ['created_at' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}
		
			$menu 				 				= json_decode(json_encode($content->data), true);
			$this->layout->page 				= view('pages.menu.create', compact('id', 'menu', 'data'));
		}

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		if(Input::has('app_id'))
		{
			$app_id 							= Input::get('app_id');
		}
		else
		{
			App::abort(404);
		}

		$result									= $this->dispatch(new Getting(new Application, ['ID' => $app_id], ['created_at' => 'asc'] ,1, 1));

		$content 								= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		$attributes 							= Input::only('name', 'tag', 'description');
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new Menu, $attributes, $id, new Application, $app_id));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Menu', $value2);
					}
				}
				else
				{
					$errors->add('Menu', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();

			return Redirect::route('hr.application.menus.show', [$is_success->data->id)->with('local_msg', $errors)->with('alert_success', 'Menu "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
	{
		if(Input::has('app_id'))
		{
			$app_id 							= Input::get('app_id');
		}
		else
		{
			App::abort(404);
		}

		$result								= $this->dispatch(new Getting(new Menu, ['ID' => $id, 'applicationid' => $app_id], ['created_at' => 'asc'] ,1, 1));

		$content 							= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		$this->layout->page 				= view('pages.menu.show', compact('data', 'id'));

		return $this->layout;
	}

	public function edit($id)
	{
		return $this->create($id);
	}

	public function destroy($id)
	{
		if(Input::has('app_id'))
		{
			$app_id 							= Input::get('app_id');
		}
		else
		{
			App::abort(404);
		}
		
		$attributes 						= ['username' => Session::get('user.username'), 'password' => Input::get('password')];

		$results 							= $this->dispatch(new Checking(new Person, $attributes));

		$content 							= json_decode($results);

		if($content->meta->success)
		{
			$result								= $this->dispatch(new Getting(new Menu, ['ID' => $id, 'applicationid' => $app_id], ['created_at' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}

			$results 						= $this->dispatch(new Deleting(new Menu, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.application.menus.index')->with('alert_success', 'Menu "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}
}
