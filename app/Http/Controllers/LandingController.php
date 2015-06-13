<?php namespace App\Http\Controllers;

class LandingController extends BaseController 
{

	protected $controller_name 	= 'landing';

	function get()
	{		
		$this->layout->page 	= view('pages.landing.index');

		return $this->layout;
	}
}
