<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Console\Commands\Checking;
use App\Console\Commands\Getting;
use App\Models\Person;
use App\Models\API;
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

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			return Response::json(['message' => 'Server Error'], 500);
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
		
		return Response::json(['message' => 'Server Error'], 500);
	}
}
