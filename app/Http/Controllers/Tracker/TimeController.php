<?php namespace App\Http\Controllers\Tracker;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Console\Commands\Saving;
use App\Models\API;
use Auth, Input, Session, Redirect, Response, DateTimeZone, DateTime;

class TimeController extends BaseController {

	function test()
	{
		$attributes 							= Input::only('application');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json(['message' => 'Server Error'], 500);
		}		

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['station_id']))
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

		$GMT 										= new DateTimeZone("GMT");
		$date 										= new DateTime( "now", $GMT );

		return Response::json(['message' => 'sukses|'.$date->format('Y/m/d H:i:s')], 200);
	}
}
