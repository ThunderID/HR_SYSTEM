<?php namespace App\Http\Controllers\Admin;

use Auth;

class LoginController extends Controller {

	function getLogin() 
	{
		$this->layout->page 	= view('pages.login.login');

		return $this->layout;
	}

	function postLogin()
	{

	}

	function getLogout()
	{
		
	}

}
