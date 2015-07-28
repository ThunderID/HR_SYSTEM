<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Models\Person;
use API, Auth, Input, Session, Redirect;

class LoginController extends BaseController {

	function getLogin() 
	{
		if (Session::has('loggedUser')) {
			return Redirect::route('hr.organisations.index');
		}
		else {
			$this->layout->page 	= view('pages.login.login');
		}

		return $this->layout;
	}

	function postLogin()
	{
		$username 					= Input::get('username');
		$password 					= Input::get('password');
		$results 					= $this->dispatch(new Checking(new Person, ['username' => $username, 'password' => $password]));
		$content 					= json_decode($results);

		if($content->meta->success)
		{
			Session::put('loggedUser', $content->data->id);
			return Redirect::intended(route('hr.organisations.index'));
		}
		
		return Redirect::back()->withErrors(['Username dan password yang anda masukkan tidak cocok dengan data kami. Harap anda memeriksa data masukkan dan mencoba lagi.']);	
	}

	function getLogout()
	{
		Session::flush();

		return Redirect::route('hr.login');
	}

}
