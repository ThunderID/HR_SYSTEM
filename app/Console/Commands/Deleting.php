<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth, Exception;
use Illuminate\Support\MessageBag;

class Deleting extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($model, $id = null)
	{
		$this->errors 					= new MessageBag();
		
		// model
		$this->model 					= $model;
		$this->validate_model($model);

		$this->id 						= $id;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{		
		if($this->errors->count())
		{
			$response 					= new APIResponse((array)$this->model, $this->errors->toArray(), ['page' => 1, 'per_page' => 1]);
			return $response->toJson();
		}

		return $this->delete();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function delete()
	{
		//
		if(!is_array($this->id))
		{
			if ($this->id)
			{
				// check if object with id available
				$this->model 			= $this->model->find($this->id);

				if(!$this->model)
				{
					$response 			= new APIResponse((array)$this->id, ['Data not Found'], 1);
					return $response->toJson();
				}
			}
			$deleted_model 				= $this->model->toArray();
			if($this->model->delete())
			{
				$response 				= new APIResponse((array)$deleted_model, null, 1);
			}
			else
			{
				$response 				= new APIResponse((array)$deleted_model, $this->model->getError(), 1);
			}

			return $response->toJson();
		}
		else
		{
			$deleted_model 				= $this->model->whereIn('id',$this->id)->get()->toArray();
			$data 						= $this->model->destroy($this->id);
			if(!$data)
			{
				dd($data);
				$response 				= new APIResponse((array)$this->id, ['Data not Found'], 1);
				return $response->toJson();
			}
			
			$deleted_model 				= $this->model->toArray();
			if($this->model->delete())
			{
				$response 				= new APIResponse((array)$deleted_model, null, 1);
			}
			else
			{
				$response 				= new APIResponse((array)$deleted_model, $this->model->getError(), 1);
			}

			return $response->toJson();
		}
	}

	private function validate_model()
	{
		if (!isset($this->model))
		{
			$this->errors->add(13, "Model does not exist");
		}
	}
}