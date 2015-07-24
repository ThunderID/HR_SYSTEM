<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Http\Controllers\Controller;
use Auth, Route, Input;

abstract class BaseController extends Controller {
	protected $layout;

	function __construct() 
	{
		if (Input::has('org_id')|(Route::is('hr.organisations.create'))|(Route::is('hr.organisations.edit'))|(Route::is('hr.organisations.index'))|(Route::is('hr.password.get'))|(Route::is('hr.applications.index'))|(Route::is('hr.applications.create'))|(Route::is('hr.applications.edit'))|(Route::is('hr.applications.show'))|(Route::is('hr.authgroups.index'))|(Route::is('hr.authgroups.create'))|(Route::is('hr.authgroups.edit'))|(Route::is('hr.authgroups.show'))|(Route::is('hr.application.menus.index'))|(Route::is('hr.application.menus.create'))|(Route::is('hr.application.menus.edit'))|(Route::is('hr.application.menus.show'))|(Route::is('hr.infomessage.index')))
		{			
			$this->layout = view('page_templates.page_template');
		}
		else
		{
			$this->layout = view('page_templates.page_template_no_nav');
		}
	}

}
