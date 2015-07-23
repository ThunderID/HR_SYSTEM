<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Checking;
use App\Console\Commands\Deleting;
use App\Models\AuthGroup;
use App\Models\GroupMenu;
use App\Models\Menu;
use App\Models\Person;

class AuthGroupController extends BaseController 
{

	protected $controller_name 					= 'authgroup';

	public function index()
	{		
		$this->layout->page 					= view('pages.authgroup.index');

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
			$result								= $this->dispatch(new Getting(new AuthGroup, ['ID' => $id], ['id' => 'asc'] ,1, 1));

			$content 							= json_decode($result);

			if(!$content->meta->success)
			{
				App::abort(404);
			}
		
			$data 				 				= json_decode(json_encode($content->data), true);
		}

		$this->layout->page 					= view('pages.authgroup.create', compact('id', 'data'));

		return $this->layout;
	}

	public function store($id = null)
	{
		if(Input::has('id'))
		{
			$id 								= Input::get('id');
		}

		$attributes 							= Input::only('name');
		$person_id 								= Session::get('loggedUser');
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new AuthGroup, $attributes, $id));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('AuthGroup', $value2);
					}
				}
				else
				{
					$errors->add('AuthGroup', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();

			return Redirect::route('hr.authgroups.show', [$is_success->data->id])->with('local_msg', $errors)->with('alert_success', 'Auth Group "' . $is_success->data->name. '" sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function show($id = null)
	{
		$result								= $this->dispatch(new Getting(new AuthGroup, ['ID' => $id, 'withattributes' => 'menus'], ['id' => 'asc'] ,1, 1));

		$content 							= json_decode($result);

		if(!$content->meta->success)
		{
			App::abort(404);
		}

		$data 				 				= json_decode(json_encode($content->data), true);

		if(Input::has('type') && (strtolower(Input::Get('type')) == 'check' || strtolower(Input::Get('type')) == 'uncheck'))
		{
			if(Input::has('menu_id'))
			{
				$menuid 	 				= Input::get('menu_id');
			}
			elseif(strtolower(Input::Get('type')) == 'check')
			{
				App::abort(404);
			}

			if(Input::has('auth_group_id'))
			{
				$authgroupid 	 			= Input::get('auth_group_id');
			}
			else
			{
				App::abort(404);
			}

			if(Input::has('group_menu_id'))
			{
				if((int)Input::get('group_menu_id'))
				{
					$groupmenuid 	 		= Input::get('group_menu_id');
				}
				else
				{
					$groupmenuid 	 		= null;
				}
			}
			elseif(strtolower(Input::Get('type')) == 'uncheck')
			{
				App::abort(404);
			}

			if(strtolower(Input::Get('type')) == 'check')
			{
				return $this->storemenu($authgroupid, $menuid, Input::get('page'));
			}
			elseif(strtolower(Input::Get('type')) == 'uncheck')
			{
				return $this->destroymenu($authgroupid, $groupmenuid, Input::get('page'));
			}
			else
			{
				App::abort(404);
			}
		}

		$this->layout->page 				= view('pages.authgroup.show', compact('data', 'id'));

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
			$results 						= $this->dispatch(new Deleting(new AuthGroup, $id));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.authgroups.index')->with('alert_success', 'Auth Group "' . $contents->data->name. '" sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors(['Password yang Anda masukkan tidak sah!']);
		}
	}

	public function storemenu($authgroupid, $menuid, $page = 1)
	{
		$attributes 							= ['tmp_auth_group_id' => $authgroupid];
		
		$errors 								= new MessageBag();
		
		DB::beginTransaction();

		$content 								= $this->dispatch(new Saving(new GroupMenu, $attributes, null, new Menu, $menuid));

		$is_success 							= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('GroupMenu', $value2);
					}
				}
				else
				{
					$errors->add('GroupMenu', $value);
				}
			}
		}

		if(!$errors->count())
		{
			DB::commit();

			return Redirect::route('hr.authgroups.show', [$authgroupid, 'page' => $page])->with('local_msg', $errors)->with('alert_success', 'Auth Group sudah disimpan');
		}

		DB::rollback();
		return Redirect::back()->withErrors($errors)->withInput();
	}

	public function destroymenu($authgroupid, $groupmenuid, $page = 1)
	{
		$results							= $this->dispatch(new Getting(new GroupMenu, ['ID' => $groupmenuid, 'authgroupid' => $authgroupid], ['tmp_auth_group_id' => 'asc'] ,1, 1));

		$content 							= json_decode($results);

		if($content->meta->success)
		{
			$results 						= $this->dispatch(new Deleting(new GroupMenu, $groupmenuid));
			$contents 						= json_decode($results);

			if (!$contents->meta->success)
			{
				return Redirect::back()->withErrors($contents->meta->errors);
			}
			else
			{
				return Redirect::route('hr.authgroups.show', [$authgroupid, 'page' => $page])->with('alert_success', 'Group Menu  sudah dihapus');
			}
		}
		else
		{
			return Redirect::back()->withErrors($content->meta->errors);
		}
	}
}
