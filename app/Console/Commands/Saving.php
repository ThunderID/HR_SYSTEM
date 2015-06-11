<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth, Exception;

class Saving extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($model, $attributes, $id = null, $syncModel = null, $syncid = null, $pivot = null)
	{
		if (!is_null($pivot))
		{
			$this->pivot = $pivot;
		}

		// attributes
		$this->attributes 	= $attributes;

		// model
		$this->model 		= $model;
		$this->validate_model($model);

		// check if object with id available
		if (!is_null($id))
		{
			$this->model 	= $this->model->findorfail($id);
		}

		if (!is_null($syncModel))
		{
			$this->syncModel = $syncModel;
			$this->validate_model($syncModel);
			$this->syncid 	= $syncid;
		}

		if (!is_array($attributes))
		{
			throw new Exception("Attributes must be type of array", 14);
		}

		$this->id 			= $id;
	}


	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{		
		return $this->save();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function save()
	{
		$this->model->fill($this->attributes);
		//
		if($this->model->save())
		{
			if(isset($this->syncModel))
			{
				if($this->syncModel == new \ThunderID\Organisation\Models\Branch)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Branch()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Organisation\Models\Organisation)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Organisation()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Person\Models\Person)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					if(isset($this->pivot))
					{
						$this->model->Person()->save($this->syncModel, $this->pivot);
					}
					else
					{
						$this->model->Person()->associate($this->syncModel);
					}
				}
				elseif($this->syncModel == new \ThunderID\Doclate\Models\Document)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Document()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Doclate\Models\PersonDocument)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->PersonDocument()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Organisation\Models\Chart)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Chart()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Schedule\Models\Calendar)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Calendar()->associate($this->syncModel);
				}
				elseif($this->syncModel == new \ThunderID\Chauth\Models\Menu)
				{
					$this->syncModel = $this->syncModel->findorfail($this->syncid);
					$this->model->Menu()->associate($this->syncModel);
				}
			}

			if($this->model->save())
			{
				$response = new APIResponse($this->model->toArray(), null, 1);
				return $response->toJson();
			}
			
			$response = new APIResponse($this->attributes, $this->model->getError()->toArray(), 1);
			return $response->toJson();
		}
		
		$response = new APIResponse($this->attributes, $this->model->getError()->toArray(), 1);
		return $response->toJson();
	}

	private function validate_model()
	{
		if (!isset($this->model))
		{
			throw new Exception("Model does not exist", 13);
		}
	}

}