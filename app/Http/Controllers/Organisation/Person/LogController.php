<?php namespace App\Http\Controllers\Organisation\Person;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image, Response;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Log;
use App\Models\ErrorLog;

class LogController extends BaseController 
{

	protected $controller_name 					= 'log';

	public function index($page = 1, $search = null, $sort = null, $per_page = 12)
	{		
		$contents 								= $this->dispatch(new Getting(new Log, $search, $sort ,(int)$page, $per_page));

		print_r($contents);exit;
		
		return $contents;
	}

	public function store($id = null)
	{		
		return Response::json(['message' => 'Sukses'], 200);
		// $attributes 							= Input::Only('name','on','pc');
	}
}
