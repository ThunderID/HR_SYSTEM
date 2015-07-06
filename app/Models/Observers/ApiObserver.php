<?php namespace App\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class ApiObserver
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['client']) && isset($model['attributes']['id']))
			{
				$validator 		= Validator::make(['client' => $model['attributes']['client']], ['client' => 'unique:apis,client,'.$model['attributes']['id']]);

				if(!$validator->passes())
				{
					$model['errors']	= $validator->errors();

					return false;
				}

				$validator 				= Validator::make($model['attributes'], ['macaddress' => 'unique:apis,macaddress,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : ''), 'pc_name' => 'unique:apis,pc_name,'.(isset($model['attributes']['id']) ? $model['attributes']['id'] : '')], ['macaddress.unique' => 'MacAddress sudah terdaftar', 'pc_name.unique' => 'Nama PC sudah terdaftar']);

				if ($validator->passes())
				{
					return true;
				}
				
				$model['errors'] 		= $validator->errors();

				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}
}
