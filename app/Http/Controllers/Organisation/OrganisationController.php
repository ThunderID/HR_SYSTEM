<?php namespace App\Http\Controllers\Organisation;

use Input, Session, App, Paginator, Redirect;
use API, DB;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use App\Http\Controllers\BaseController;

class OrganisationController extends BaseController {

	protected $controller_name = 'organisasi';

	function index($page = 1)
	{
		// ---------------------- LOAD DATA ----------------------
		$search 									= ['000' => 1, 'withattributes' => ['branches', 'branches.charts']];
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
			$sort 									= ['name' => 'asc'];
		}

		$per_page 									= 12;

		$contents 									= $this->dispatch(new Getting(new Organisation, $search, $sort ,(int)$page, $per_page));
		
		// return $contents;
		dd($contents);
	}

	function getChoice()
	{		
		$this->layout->page 	= view('pages.choice_organisasi.choice_organisasi');

		return $this->layout;
	}
}
