<?php namespace App\Models\Observers;

use \Validator;
use App\Models\PersonWidget;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Updating						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonWidgetObserver
{
	public function saving($model)
	{
		$validator 						= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			if(json_decode(json_encode($model['attributes']['query']) , true ))
			{
				return true;
			}
			else
			{
				$model['errors'] 		= ['Query harus dalam format json'];

				return false;
			}
		}
		else
		{
			$model['errors'] 			= $validator->errors();

			return false;
		}
	}

	public function updating($model)
	{
		//cek aturan UI
		
		return true;
	}

	public function deleting($model)
	{
		//cek aturan UI
		
		return true;
	}
}
