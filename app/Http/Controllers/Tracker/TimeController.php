<?php namespace App\Http\Controllers\Tracker;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Console\Commands\Saving;
use App\Models\API;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Branch;
use App\Models\IPWhitelist;
use App\Models\IPWhitelistLog;
use App\Models\Log;
use App\Models\ErrorLog;
use Auth, Input, Session, Redirect, Response, DateTimeZone, DateTime, DB;

class TimeController extends BaseController {

	function test()
	{
		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 200);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 200);
		}

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'workstationaddress' => $attributes['application']['api']['station_id'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			$results_2 							= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'withattributes' => ['branch']], [], 1, 1));
		
			$content_2 							= json_decode($results_2);
			
			if(!$content_2->meta->success)
			{
				$filename                       	= storage_path().'/logs/appid.log';
				$fh                             	= fopen($filename, 'a+'); 
				$template 							= date('Y-m-d H:i:s : Test : ').json_encode($attributes['application']['api'])."\n";
		        fwrite($fh, $template); 
		        fclose($fh);

				return Response::json('401', 200);
			}
			else
			{
				return Response::json('201', 200);
			}
		}

		$GMT 										= new DateTimeZone("GMT");
		$date 										= new DateTime( "now", $GMT );

		return Response::json('sukses|'.$date->format('Y/m/d H:i:s'), 200);
	}

	public function testv3()
	{
		$attributes 							= Input::only('application', 'log');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 200);
		}

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 200);
		}	

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'workstationaddress' => $attributes['application']['api']['station_id'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			$elog['on']							= date('Y-m-d H:i:s');
			$elog['message']					= 'Upaya Masuk';
			$elog['ip'] 						= $_SERVER['REMOTE_ADDR'];
			$saved_error_log 					= $this->dispatch(new Saving(new ErrorLog, $elog, null));

			return Response::json('402', 200);
		}

		if(isset($attributes['application']['api']['tr_ver']) && strtolower($attributes['application']['api']['tr_ver'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['tr_ver']);

			$content_2 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($content_2);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}

		$organisationid 						= $content->data->branch->organisation_id;

		//cek apa ada data log
		if(!$attributes['log'])
		{
			return Response::json('103', 200);
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
				if(isset($value[4]))
				{
					$log['last_input_time']		= date("Y-m-d H:i:s", strtotime($value[4]));
					$log['app_version']			= $value[5];
				}
				$log['ip'] 						= $_SERVER['REMOTE_ADDR'];
				$data 							= $this->dispatch(new Getting(new Person, ['username' => $value[0]], [] , 1, 1), false);
				$person 						= json_decode($data);

				if(!$person->meta->success)
				{
					$log['email']				= $value[0];
					$log['message']				= 'Pengirim tidak terdaftar dalam sistem';
					$saved_error_log 			= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $organisationid));
				}
				else
				{
					$data 						= $this->dispatch(new Getting(new IPWhitelist, ['ip' => $_SERVER['REMOTE_ADDR']], [] , 1, 1), false);
					$ip 						= json_decode($data);
					if(!$ip->meta->success)
					{
						$log['email']			= $value[0];
						$log['message']			= 'Pengirim tidak terdaftar dalam IP Whitelist';
						$saved_error_log 		= $this->dispatch(new Saving(new IPWhitelistLog, $log, null));
					}
					else
					{
						$saved_log 				= $this->dispatch(new Saving(new Log, $log, null, new Person, $person->data->id));
						$is_success_2 			= json_decode($saved_log);
						if(!$is_success_2->meta->success)
						{
							$log['email']		= $value[0];
							$log['message']		= json_encode($is_success_2->meta->errors);
							$saved_error_log 	= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $organisationid));
						}
						
					}
				}
			}
		}

		DB::commit();
		return Response::json('Sukses', 200);
	}
}
