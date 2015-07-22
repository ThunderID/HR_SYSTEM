<?php namespace App\Http\Controllers\Tracker;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Console\Commands\Saving;
use App\Models\Person;
use App\Models\API;
use App\Models\Work;
use App\Models\WorkAuthentication;
use App\Models\Branch;
use Auth, Input, Session, Redirect, Response, Config;

class LoginController extends BaseController {

	function postLogin()
	{

		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 200);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['tr_ver']) || !isset($attributes['application']['api']['station_id']) || !isset($attributes['application']['api']['email']) || !isset($attributes['application']['api']['password']))
		{
			return Response::json('102', 500);
		}

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'workstationaddress' => $attributes['application']['api']['station_id'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
	        $filename                       	= storage_path().'/logs/appid.log';
			$fh                             	= fopen($filename, 'a+'); 
			$template 							= date('Y-m-d H:i:s : Login : ').json_encode($attributes['application']['api'])."\n";
	        fwrite($fh, $template); 
	        fclose($fh);

			return Response::json('402', 200);
		}

		if(strtolower($attributes['application']['api']['tr_ver'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['tr_ver']);

			$contents 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($contents);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}

		$organisationid 						= $content->data->branch->organisation_id;

		$email 									= $attributes['application']['api']['email'];
		$password 								= $attributes['application']['api']['password'];
		$results 								= $this->dispatch(new Checking(new Person, ['username' => $email, 'password' => $password]));
		$content 								= json_decode($results);

		if($content->meta->success)
		{
			$results 							= $this->dispatch(new Getting(new Work, ['personid' => $content->data->id, 'active' => true, 'withattributes' => ['workauthentications', 'workauthentications.organisation', 'chart']], ['end' => 'asc'],1, 100));

			$contents_2 						= json_decode($results);

			if(!$contents_2->meta->success || !count($contents_2->data))
			{
				return Response::json('403', 200);
			}

			$workid										= [];
			$chartids									= [];
			$chartsids									= [];
			$chartnames									= [];
			$organisationids							= [];
			$organisationnames							= [];
			
			foreach ($contents_2->data as $key => $value) 
			{
				$workid[]								= $value->id;
				foreach ($value->workauthentications as $key_2 => $value_2) 
				{
					if(!isset($chartids[$value_2->organisation->id]) || !in_array($value->chart->id, $chartids[$value_2->organisation->id]))
					{
						$chartids[$value_2->organisation->id][]			= $value->chart->id;
						$chartsids[]									= $value->chart->id;
					}

					if(!isset($chartnames[$value_2->organisation->id]) || !in_array($value->chart->name, $chartnames[$value_2->organisation->id]))
					{
						$chartnames[$value_2->organisation->id][]		= $value->chart->name;
					}

					if(!in_array($value_2->organisation->name, $organisationnames))
					{
						$organisationnames[]							= $value_2->organisation->name;
					}

					if(!in_array($value_2->organisation->id, $organisationids))
					{
						$organisationids[]								= $value_2->organisation->id;
					}
				}
			}

			$results 										= $this->dispatch(new Getting(new WorkAuthentication, ['menuid' => 102, 'workid' => $workid, 'organisationid' => $organisationids], ['tmp_auth_group_id' => 'asc'],1, 1));

			$contents_3 									= json_decode($results);

			if((!$contents_3->meta->success))
			{
				return Response::json('403', 200);
			}
			else
			{
				return Response::json('Sukses', 200);
			}
		}
		else
		{
			return Response::json('404', 200);
		}

		return Response::json('404', 200);
	}


	function testLogin()
	{
		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 500);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['tr_ver']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 500);
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

		if(strtolower($attributes['application']['api']['tr_ver'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['tr_ver']);

			$content 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($content);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}

		return Response::json('Sukses', 200);
	}

	function updateVersion()
	{
		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 500);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['tr_ver']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 500);
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

		if(strtolower($attributes['application']['api']['tr_ver'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['tr_ver']);

			$content 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($content);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}
		
		if((float)$attributes['application']['api']['tr_ver'] < (float)Config::get('current.absence.version'))
		{
			return Response::json('sukses|'.Config::get('current.absence.url'), 200);
		}

		return Response::json('200', 200);
	}
}
