<?php namespace App\Http\Controllers\FP;

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
use App\Models\Finger;
use App\Models\Branch;
use App\Models\Api;

class FingerController extends BaseController 
{

	protected $controller_name 					= 'data';

	public function store()
	{		
		$attributes 							= Input::only('application', 'data');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 200);
		}

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 200);
		}	

		if(!$attributes['application']['api']['username'])
		{
			return Response::json('106', 200);
		}

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'workstationaddress' => $attributes['application']['api']['station_id'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			return Response::json('402', 200);
		}

		if(isset($attributes['application']['api']['version']) && strtolower($attributes['application']['api']['version'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['version']);

			$content_2 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($content_2);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}

		$organisationid 						= $content->data->branch->organisation_id;

		//cek apa ada data data
		if(!$attributes['data'])
		{
			return Response::json('103', 200);
		}

		//cek apa data bisa disimpan
		DB::beginTransaction();
		if(isset($attributes['data']))
		{
			$attributes['data']				= (array)$attributes['data'];
			$data 							= $this->dispatch(new Getting(new Person, ['username' => $attributes['application']['api']['username'], 'withattributes' => ['finger']], [] , 1, 1), false);
			$person 						= json_decode($data);

			if(!$person->meta->success)
			{
				$data['email']				= $value[0];
				$data['message']			= json_encode($person->meta->errors);
				$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
			}
			elseif(!$person->data->finger)
			{
				$data['email']				= $person->data->username;
				$data['message']			= json_encode(['Tidak ada data finger']);
				$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
			}
			else
			{
				$data 						= [];
				foreach ($attributes['data'] as $key => $value) 
				{
					unset($activefinger);

					switch(strtolower($key))
					{
						case 'jari1' :
							$activefinger 		= 'left_thumb';
							break;
						case 'jari2' :
							$activefinger 		= 'left_index_finger';
							break;
						case 'jari3' :
							$activefinger 		= 'left_middle_finger';
							break;
						case 'jari4' :
							$activefinger 		= 'left_ring_finger';
							break;
						case 'jari5' :
							$activefinger 		= 'left_little_finger';
							break;
						case 'jari6' :
							$activefinger 		= 'right_thumb';
							break;
						case 'jari7' :
							$activefinger 		= 'right_index_finger';
							break;
						case 'jari8' :
							$activefinger 		= 'right_middle_finger';
							break;
						case 'jari9' :
							$activefinger 		= 'right_ring_finger';
							break;
						case 'jari10' :
							$activefinger 		= 'right_little_finger';
							break;
					}

					if(isset($activefinger))
					{
						$data[$activefinger]  	= $value;
					}
				}

				$saved_data 					= $this->dispatch(new Saving(new Finger, $data, $person->data->finger->id, new Person, $person->data->id));
				$is_success_2 					= json_decode($saved_data);
				if(!$is_success_2->meta->success)
				{
					$data['email']				= $person->data->name;
					$data['message']			= $is_success_2->meta->errors;
					$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
				}
			}
		}

		DB::commit();
		return Response::json('Sukses', 200);
	}

	public function destroy()
	{		
		$attributes 							= Input::only('application', 'data');

		//cek apa ada aplication
		if(!$attributes['application'])
		{
			return Response::json('101', 200);
		}

		if(!isset($attributes['application']['api']['client']) || !isset($attributes['application']['api']['secret']) || !isset($attributes['application']['api']['station_id']))
		{
			return Response::json('102', 200);
		}	

		if(!$attributes['application']['api']['username'])
		{
			return Response::json('106', 200);
		}

		//cek API key & secret
		$results 								= $this->dispatch(new Getting(new API, ['client' => $attributes['application']['api']['client'], 'secret' => $attributes['application']['api']['secret'], 'workstationaddress' => $attributes['application']['api']['station_id'], 'withattributes' => ['branch']], [], 1, 1));
		
		$content 								= json_decode($results);
		if(!$content->meta->success)
		{
			return Response::json('402', 200);
		}

		if(isset($attributes['application']['api']['version']) && strtolower($attributes['application']['api']['version'])!=strtolower($content->data->tr_version))
		{
			$apiattributes 						= json_decode(json_encode($content->data), true);
			$apiattributes['tr_version']		= strtolower($attributes['application']['api']['version']);

			$content_2 							= $this->dispatch(new Saving(new API, $apiattributes, $apiattributes['id'], new Branch, $apiattributes['branch_id']));
			$is_success 						= json_decode($content_2);
			
			if(!$is_success->meta->success)
			{
				return Response::json('301', 200);
			}
		}

		$organisationid 						= $content->data->branch->organisation_id;

		//cek apa ada data data
		if(!$attributes['data'])
		{
			return Response::json('103', 200);
		}

		//cek apa data bisa disimpan
		DB::beginTransaction();
		if(isset($attributes['data']))
		{
			$attributes['data']				= (array)$attributes['data'];
			$data 							= $this->dispatch(new Getting(new Person, ['username' => $attributes['application']['api']['username'], 'withattributes' => ['finger']], [] , 1, 1), false);
			$person 						= json_decode($data);

			if(!$person->meta->success)
			{
				$data['email']				= $value[0];
				$data['message']			= json_encode($person->meta->errors);
				$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
			}
			elseif(!$person->data->finger)
			{
				$data['email']				= $person->data->username;
				$data['message']			= json_encode(['Tidak ada data finger']);
				$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
			}
			else
			{
				$data 						= [];
				foreach ($attributes['data'] as $key => $value) 
				{
					unset($activefinger);
					switch(strtolower($key))
					{
						case 'jari1' :
							$activefinger 		= 'left_thumb';
							break;
						case 'jari2' :
							$activefinger 		= 'left_index_finger';
							break;
						case 'jari3' :
							$activefinger 		= 'left_middle_finger';
							break;
						case 'jari4' :
							$activefinger 		= 'left_ring_finger';
							break;
						case 'jari5' :
							$activefinger 		= 'left_little_finger';
							break;
						case 'jari6' :
							$activefinger 		= 'right_thumb';
							break;
						case 'jari7' :
							$activefinger 		= 'right_index_finger';
							break;
						case 'jari8' :
							$activefinger 		= 'right_middle_finger';
							break;
						case 'jari9' :
							$activefinger 		= 'right_ring_finger';
							break;
						case 'jari10' :
							$activefinger 		= 'right_little_finger';
							break;
					}

					if(isset($activefinger))
					{
						$data[$activefinger]  	= '';
					}
				}

				$saved_data 					= $this->dispatch(new Saving(new Finger, $data, $person->data->finger->id, new Person, $person->data->id));
				$is_success_2 					= json_decode($saved_data);
				if(!$is_success_2->meta->success)
				{
					$data['email']				= $person->data->name;
					$data['message']			= $is_success_2->meta->errors;
					$saved_error_data 			= $this->dispatch(new Saving(new ErrorLog, $data, null, new Organisation, $organisationid));
				}
			}
		}
		
		DB::commit();
		return Response::json('Sukses', 200);
	}
}
