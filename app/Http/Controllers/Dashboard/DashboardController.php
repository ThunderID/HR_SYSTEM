<?php namespace App\Http\Controllers\Dashboard;

use Input, Session, App, Paginator, Redirect;
use App\Http\Controllers\BaseController;

class DashboardController extends BaseController {

	protected $controller_name = 'dashboard';

	function overview()
	{
		$this->layout->page 	= view('pages.dashboard.index');

		return $this->layout;
	}	
}
