<?php namespace App\Http\Controllers\Organisation\Report\Activity;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image, Excel;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\ProcessLog;

class LogController extends BaseController 
{
	protected $controller_name 						= 'activity';

	public function index()
	{		
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisationid');
		}

		if(Input::has('person_id'))
		{
			$person_id 								= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		if(Input::has('start'))
		{
			$start 									= date('Y-m-d', strtotime(Input::get('start')));
		}
		else
		{
			$start 									= date('Y-m-d', strtotime('first day of this month'));
		}

		if(Input::has('end'))
		{
			$end 									= date('Y-m-d', strtotime(Input::get('end')));
		}
		else
		{
			$end 									= date('Y-m-d', strtotime('last day of next month'));
		}

		if(Input::has('ondate'))
		{
			$ondate 									= date('Y-m-d', strtotime(Input::get('ondate')));
		}
		else
		{
			$ondate 									= date('Y-m-d');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		$search['id']								= $person_id;
		$search['organisationid']					= $org_id;
		$search['withattributes']					= ['organisation'];
		$sort 										= ['name' => 'asc'];
		if(Session::get('user.menuid')>=5)
		{
			$search['chartchild'] 					= Session::get('user.chartpath');
		}
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 									= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 									= json_decode(json_encode($contents->data), true);
		$data 										= $person['organisation'];

		$this->layout->page 						= view('pages.organisation.report.activity.log.index', compact('data', 'start', 'end', 'person', 'ondate'));

		if(Input::has('print'))
		{
			$search 								= ['id' => $person['id'], 'processlogsondate' => ['on' => [$start, $end]]];
			$sort 									= ['persons.name' => 'asc'];
			$page 									= 1;
			$per_page 								= 1;
			$search['organisationid'] 				= ['fieldname' => 'persons.organisation_id', 'variable' => $data['id']];
			$results 								= $this->dispatch(new Getting(new Person, $search, $sort , (int)$page, (int)$per_page, isset($new) ? $new : false));

			$contents 									= json_decode($results);

			if(!$contents->meta->success)
			{	
				App::abort(404);	
			}
			$report 								= json_decode(json_encode($contents->data), true);
			

			Excel::create('Laporan Aktivitas '.$report['name'].' per tanggal ( '.$start.' s.d '.$end.' )', function($excel) use ($report, $start, $end, $person) 
			{
				// Set the title
				$excel->setTitle('Laporan Aktivitas');
				// Call them separately
				$excel->setDescription('Laporan Aktivitas person');
				$excel->sheet('Sheetname', function ($sheet) use ($report, $start, $end, $person) 
				{
					$c 									= count($report);
					$sheet->loadView('widgets.organisation.report.attendance.person.table_csv')->with('data', $report)->with('start', $start)->with('end', $end)->with('person', $person);
				});
			})->export(Input::get('mode'));	
		}

		return $this->layout;
	}

	public function create($id = null)
	{
		$errors 								= new MessageBag();

		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisationid');
		}

		if(!in_array($org_id, Session::get('user.organisationids')))
		{
			App::abort(404);
		}

		if(Input::has('person_id'))
		{
			$person_id 							= Input::get('person_id');
		}
		else
		{
			App::abort(404);
		}

		$search['id']							= $person_id;
		$search['organisationid']				= $org_id;
		$search['withattributes']				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$person 								= json_decode(json_encode($contents->data), true);
		$data 									= $person['organisation'];

		if(!is_null($id))
		{
			if(!Input::has('ondate'))
			{
				App::abort(404);
			}

			if((int)Session::Get('user.menuid')==2)
			{
				$dateline 						= date('Y-m-d', strtotime(Input::get('ondate'). ' + 2 months'));

				if($dateline < date('Y-m-d'))
				{
					$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 2 Bulan');
					
					return Redirect::back()->withErrors($errors)->withInput();
				}
			}

			if((int)Session::Get('user.menuid')==3)
			{
				$dateline 						= date('Y-m-d', strtotime(Input::get('ondate'). ' + 7 days'));

				if($dateline < date('Y-m-d'))
				{
					$errors->add('ProcessLog', 'Batas Akhir Perubahan Status adalah 7 hari');
					
					return Redirect::back()->withErrors($errors)->withInput();
				}
			}

		}

		$this->layout->page 					= view('pages.organisation.report.attendance.person.create', compact('id', 'data', 'person'));

		return $this->layout;
	}

}
