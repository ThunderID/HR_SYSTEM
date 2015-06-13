<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Auth, Route, Input;

abstract class BaseController extends Controller {
	protected $layout;

	function __construct() 
	{
		if (Input::has('org_id'))
		{			
			$this->layout = view('page_templates.page_template');
		}
		else
		{
			$this->layout = view('page_templates.page_template_no_nav');
		}
	}

}
