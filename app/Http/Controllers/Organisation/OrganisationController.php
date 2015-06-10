<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect;
use API, DB;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Http\Controllers\Controller;

class OrganisationController extends Controller {

	protected $controller_name = 'organisasi';

	function index($page = 1)
	{
		// ---------------------- LOAD DATA ----------------------
		$search 									= ['id' => 1, 'withattributes' => ['branches', 'branches.charts']];
		if(Input::has('q'))
		{
			$search['name']							= Input::get('q');			
		}

		if(Input::get('sort'))
		{
			if(Input::get('sort') == '1')
			{
				$sort 								= ['created_at' => 'asc'];
			}
			elseif(Input::get('sort') == '2')
			{
				$sort 								= ['created_at' => 'desc'];
			}
			elseif(Input::get('sort') == '3')
			{
				$sort 								= ['name' => 'desc'];
			}
			elseif(Input::get('sort') == '4')
			{
				$sort 								= ['name' => 'asc'];
			}
			else
			{
				return Redirect::route('hr.organisations.index');
			}
		}
		else
		{
			$sort 									= ['created_at' => 'asc'];
		}

		$per_page 									= 12;

		$contents 									= $this->dispatch(new Getting(new Organisation, $search, $sort ,(int)$page, $per_page));
		
		// return $contents;
		dd($contents);
	}
}
