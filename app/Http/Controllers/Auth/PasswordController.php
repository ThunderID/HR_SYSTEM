<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Saving;
use Illuminate\Support\MessageBag as MessageBag;
use App\Models\Person;
use API, Auth, Input, Session, Redirect, Validator, Hash;

class PasswordController extends BaseController {

	function getPassword() 
	{
		$this->layout->page 		= view('pages.login.password');

		return $this->layout;
	}

	function postPassword()
	{
		$username 					= Session::get('user.username');
		$password 					= Input::get('old_password');
		$results 					= $this->dispatch(new Checking(new Person, ['username' => $username, 'password' => $password]));
		$content 					= json_decode($results);

		if($content->meta->success)
		{
			$validator 				= Validator::make(Input::all(), ['password' => 'required|min:8|confirmed']);

			if($validator->passes())
			{
				$attributes['password']							= Hash::make(Input::Get('password'));
				$attributes['last_password_updated_at']			= date('Y-m-d H:i:s');
				$content 						= $this->dispatch(new Saving(new Person, $attributes, Session::get('loggedUser')));

				$errors 						= new MessageBag;
				$is_success 					= json_decode($content);
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
					return Redirect::route('hr.organisations.index')->with('alert_success', 'Password baru sudah disimpan');
				}
				return Redirect::back()->withErrors($errors);	
			}
			return Redirect::back()->withErrors($validator->errors());	
		}
		return Redirect::back()->withErrors(['Password yang anda masukkan tidak cocok dengan data kami. Harap anda memeriksa data masukkan dan mencoba lagi.']);	
	}
}
