<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Console\Commands\Saving;
use App\Models\Person;
use App\Models\API;
use App\Models\Branch;
use Auth, Input, Session, Redirect, Response;

class TrackerController extends BaseController {

	function postLogin()
	{

		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['tr_ver']) || !isset($attributes['application']['api']['station_id']) || !isset($attributes['application']['api']['email']) || !isset($attributes['application']['api']['password']))
		{
			return Response::json(['message' => 'Server Error'], 500);
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

			return Response::json(['message' => 'Server Error'], 500);
		}

		if(strtolower($attributes['application']['api']['tr_ver'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['tr_ver']);

			$contents 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($contents);
			
			if(!$is_success->meta->success)
			{
				return Response::json(['message' => 'Server Error'], 500);
			}
		}

		$organisationid 						= $content->data->branch->organisation_id;

		$email 									= $attributes['application']['api']['email'];
		$password 								= $attributes['application']['api']['password'];
		$results 								= $this->dispatch(new Checking(new Person, ['username' => $email, 'password' => $password]));
		$content 								= json_decode($results);

		if($content->meta->success)
		{
			return Response::json(['message' => 'Sukses'], 200);
		}
		else
		{
			return Response::json(['message' => 'Gagal'], 200);
		}

		return Response::json(['message' => 'Server Error'], 500);
	}


	function testLogin()
	{
		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			$filename                       	= storage_path().'/logs/appid.log';
			$fh                             	= fopen($filename, 'a+'); 
			$template 							= date('Y-m-d H:i:s : No Log : ');
	        fwrite($fh, $template); 
	        fclose($fh);

			return Response::json(['message' => 'Server Error'], 500);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['tr_ver']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json(['message' => 'Server Error'], 500);
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

				return Response::json(['message' => 'Server Error'], 500);
			}
			else
			{
				return Response::json(['message' => 'Konek'], 200);
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
				return Response::json(['message' => 'Server Error'], 500);
			}
		}

		return Response::json(['message' => 'Sukses'], 200);
	}
}
