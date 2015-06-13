<?php namespace App\Http\Controllers;

use Input, Session, App, Paginator, Redirect;
use App\Http\Controllers\BaseController;

class OrganisationController extends BaseController {

	protected $controller_name = 'organisasi';

	function show()
	{
		$this->layout->page 	= view('pages.dashboard.index');

		return $this->layout;
	}
}
