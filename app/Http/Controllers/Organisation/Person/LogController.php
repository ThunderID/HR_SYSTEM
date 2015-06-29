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
use App\Models\Person;
use App\Models\ErrorLog;
use App\Models\Api;

class LogController extends BaseController 
{

	protected $controller_name 					= 'log';

	public function index($page = 1, $search = null, $sort = null, $per_page = 12)
	{		
		$contents 								= $this->dispatch(new Getting(new Log, $search, $sort ,(int)$page, $per_page));

		return $contents;
	}

	public function store()
	{		
		$attributes 							= Input::only('application', 'log');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}		

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		$organisationid 						= $content->data->branch->organisation_id;

		//cek apa ada data log
		if(!$attributes['log'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}

		//cek apa data bisa disimpan
		DB::beginTransaction();
		if(isset($attributes['log']))
		{
			$attributes['log']					= (array)$attributes['log'];
			foreach ($attributes['log'] as $key => $value) 
			{
				$log['name']					= strtolower($value[1]);
				$log['on']						= date("Y-m-d H:i:s", strtotime($value[2]));
				$log['pc']						= $value[3];
				$data 							= $this->dispatch(new Getting(new Person, ['email' => $value[0]], [] , 1, 1));
				$person 						= json_decode($data);

				if(!$person->meta->success)
				{
					$log['email']				= $value[0];
					$log['message']				= json_encode($person->meta->errors);
					$saved_error_log 			= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $organisationid));
				}
				else
				{
					$saved_log 					= $this->dispatch(new Saving(new Log, $log, null, new Person, $person->data->id));
					$is_success_2 				= json_decode($saved_log);
					if(!$is_success_2->meta->success)
					{
						$log['email']			= $value[0];
						$log['message']			= $is_success_2->meta->errors;
						$saved_error_log 		= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $organisationid));
					}
				}
			}
		}
		DB::commit();
		return Response::json(['message' => 'Sukses'], 200);
	}
}
