<?php namespace App\Models\Observers;

use DB, Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class QueueObserver 
{
	public function saving($model)
	{
		$validator 						= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['created_by']) && $model['attributes']['created_by']!=0)
			{
				$validator 				= Validator::make($model['attributes'], ['created_by' => 'exists:persons,id']);
			
				if (!$validator->passes())
				{
					$model['errors'] 	= $validator->errors();

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
