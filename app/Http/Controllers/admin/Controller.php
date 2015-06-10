<?php namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller as BaseController;
use Auth, Route;

abstract class Controller extends BaseController {
	protected $layout;

	function __construct() 
	{
		if (!Route::is('admin.login'))
		{
			$this->layout = view('page_templates.page_templates');
			$this->layout->page = view('pages.choice_organisation.choice_organisation');
		}
		else
		{
			$this->layout = view('page_templates.page_template_no_nav');
		}
	}

}
