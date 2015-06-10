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
			}
			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}
}
