<?php namespace App\Http\Controllers\Organisation\Report;

use Input, Session, App, Paginator, Redirect, DB, Config, Validator, Image;
use App\Http\Controllers\BaseController;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Console\Commands\Deleting;
use App\Console\Commands\Checking;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Work;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\Contact;
use App\Models\PersonSchedule;

class WageController extends BaseController 
{
	protected $controller_name 						= 'wages';

	public function index()
	{		
		// ---------------------- LOAD DATA ----------------------
		if(Input::has('org_id'))
		{
			$org_id 								= Input::get('org_id');
		}
		else
		{
			$org_id 								= Session::get('user.organisation');
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}
		
		if(Input::has('start'))
		{
			list($d,$m,$y) 							= explode('/', Input::get('start'));
			$start 									= "$y-$m-$d";
			list($d,$m,$y) 							= explode('/', Input::get('end'));
			$end 									= "$y-$m-$d";

			$search 								= ['quotas' => ['ondate'=> [$start, $end]]];
		}
		else
		{
			$start 									= date('Y-m-d');
			$end 									= date('Y-m-d');
			$search 								= ['quotas' => ['ondate'=> [$start, $end]]];
		}

		$sort 										= ['persons.id' => 'desc'];
		if(Input::has('case'))
		{
			switch (Input::get('case'))
			{
				case 'late':
					$search['late'] 				= true;
					$sort 							= ['margin_start' => 'asc'];
				break;
				case 'ontime':
					$search['ontime']			 	= true;
					$sort 							= ['margin_start' => 'desc'];
				break;
				case 'earlier':
					$search['earlier'] 				= true;
					$sort 							= ['margin_end' => 'asc'];
				break;
				case 'overtime':
					$search['overtime'] 			= true;
					$sort 							= ['margin_end' => 'desc'];
				break;
				default:
					App::abort('404');
				break;
			}
		}

		if(Input::has('branch'))
		{
			$search['branchid'] 					= Input::get('branch');
		}
		if(Input::has('tag'))
		{
			$search['charttag'] 					= Input::get('tag');
		}

		$search['organisationid'] 					= $org_id;

		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 100));
		$contents 									= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 										= json_decode(json_encode($contents->data), true);
		unset($search);


		$search['organisationid'] 					= $org_id;
		$search['checkwork'] 						= true;

		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 100));
		$contents 									= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}
		$persons 									= json_decode(json_encode($contents->data), true);
		unset($search);

		$ids 										= [];

		foreach ($persons as $key => $value)
		{
			$ids[] 									= $value['id'];
		}
		
		$search 									= ['minusquotas' => ['ondate'=> [$start, $end], 'ids' => $ids]];
		$sort 										= ['persons.id' => 'desc'];
		$results 									= $this->dispatch(new Getting(new Person, $search, $sort , 1, 100));
		$contents 									= json_decode($results);
		
		if(!$contents->meta->success)
		{
			App::abort(404);
		}
		
		$data2 										= json_decode(json_encode($contents->data), true);
		$currentstatus 								= [];
		$compelete 									= [];

		foreach ($persons as $key => $value)
		{
			$minus 									= 0;
			$quota 									= 0;
			$plus 									= 0;
			$status 								= null;
			foreach ($data as $key1 => $value1)
			{
				if($value1['id']==$value['id'])
				{
					$quota 							= $quota + $value1['quota'];
					$plus 							= $plus + $value1['plus_quota'];
				}
			}
			foreach ($data2 as $key2 => $value2)
			{
				if($value2['person_id']==$value['id'])
				{
					$status[$value2['name']] 		= $value2['minus_quota'];
					$minus 							= $minus + $value2['minus_quota'];
					if(!in_array($value2['name'], $currentstatus))
					{
						$currentstatus[] 			= $value2['name'];
					}
				}
			}
			$compelete[$key] 						= $value;
			$compelete[$key]['quota'] 				= $quota;
			$compelete[$key]['plus_quota'] 			= $plus;
			$compelete[$key]['minus_quota'] 		= $minus;
			$compelete[$key]['residue_quota'] 		= $compelete[$key]['quota'] + $compelete[$key]['plus_quota'] - $compelete[$key]['minus_quota'];
			$compelete[$key]['status'] 				= $status;
		}

		$search 									= ['organisationid' => $org_id];
		if(Input::has('branch'))
		{
			$search['id'] 							= Input::get('branch');
			$search['DisplayDepartments']		 	= '';
		}

		$sort 										= ['name' => 'asc'];
		$results_2 									= API::branch()->index(1, $search, $sort);
		$contents_2 								= json_decode($results_2);
		if(!$contents_2->meta->success)
		{
			App::abort(404);
		}

		$branches 									= json_decode(json_encode($contents_2->data), true);
		if(Input::has('mode'))
		{
			Excel::create('Wages Reports', function($excel) use ($compelete, $currentstatus, $start, $end) 
			{
				// Set the title
				$excel->setTitle('Our new awesome title');
				// Call them separately
				$excel->setDescription('A demonstration to change the file properties');
				$excel->sheet('Sheetname', function ($sheet) use ($compelete, $currentstatus, $start, $end) 
				{
					$c 								= count($compelete);
					$cs 							= count($currentstatus);
					$sheet->setBorder('A1:G'.($c+2), 'thin');
					$sheet->setWidth(['A' => 5, 'B' => 30, 'C' => 15, 'D' => 15, 'E' => 15, 'F' => 15, 'G' => 15]);
					$sheet->loadView('admin.pages.reports.wages.wages_csv')->with('data', $compelete)->with('status', $currentstatus)->with('cs', $cs)->with('start', $start)->with('end', $end);
				});
			})->export(Input::get('mode'));
		}
		
		// ---------------------- GENERATE CONTENT ----------------------
		$this->layout->page 						= view('pages.report.wages.index', compact('id', 'data', 'compelete', 'currentstatus', 'start', 'end'));
		
		return $this->layout;
	}

	public function create($id = null)
	{
		if(Input::has('org_id'))
		{
			$org_id 							= Input::get('org_id');
		}
		else
		{
			$org_id 							= Session::get('user.organisation');
		}

		if(!in_array($org_id, Config::get('user.orgids')))
		{
			App::abort(404);
		}

		$search['id']							= $org_id;
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
		$contents 								= json_decode($results);		

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$data 									= json_decode(json_encode($contents->data), true);

		$this->layout->page 					= view('pages.person.create', compact('id', 'data'));

		return $this->layout;
	}
}
