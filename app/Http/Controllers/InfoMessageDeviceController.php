<?php namespace App\Http\Controllers;

use Input, Config, App, Paginator, Redirect, DB, Session;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;

class InfoMessageDeviceController extends BaseController 
{

	protected $controller_name 					= 'infomessagedevice';

	public function index()
	{		
		$this->layout->page 					= view('pages.infomessage.index');

		return $this->layout;
	}
}
