<?php namespace App\Http\Controllers;

use Input, Session, App, Paginator, Redirect;
use App\Http\Controllers\BaseController;

class OrganisationController extends BaseController 
{

	protected $controller_name = 'organisasi';

	public function index()
	{		
		$this->layout->page 	= view('pages.organisation.index');

		return $this->layout;
	}

	public function show()
	{
		$this->layout->page 	= view('pages.organisation.show');

		return $this->layout;
	}
}
