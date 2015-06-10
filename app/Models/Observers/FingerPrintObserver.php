<?php namespace App\Models\Observers;

use DB, Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * ---------------------------------------------------------------------- */

class FingerPrintObserver 
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(isset($model['attributes']['branch_id']) && isset($model['attributes']['id']))
			{
				$validator 		= Validator::make(['branch_id' => $model['attributes']['branch_id']], ['branch_id' => 'unique:finger_prints,branch_id,'.$model['attributes']['id']]);

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
