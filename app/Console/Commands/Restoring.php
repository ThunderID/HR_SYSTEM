<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth, Exception;
use Illuminate\Support\MessageBag;

class Restoring extends Command implements SelfHandling {

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

		return $this->restore();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function restore()
	{
		//
		if(!is_array($this->id))
		{
			if ($this->id)
			{
				// check if object with id available
				$this->model 			= $this->model->withTrashed()->find($this->id);

				if(!$this->model)
				{
					$response 			= new APIResponse((array)$this->id, ['Data not Found'], 1);
					return $response->toJson();
				}
			}
			$restored_model 			= $this->model->toArray();
			if($this->model->restore())
			{
				$response 				= new APIResponse((array)$restored_model, null, 1);
			}
			else
			{
				$response 				= new APIResponse((array)$restored_model, (array)$this->model->getError(), 1);
			}

			return $response->toJson();
		}
		else
		{
			$this->errors->add(14, "ID Must be string");
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