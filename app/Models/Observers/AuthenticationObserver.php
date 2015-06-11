<?php namespace App\Models\Observers;

use \Validator;
use Illuminate\Support\MessageBag;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Updating						
 * ---------------------------------------------------------------------- */

class AuthenticationObserver
{
	public function saving($model)
	{
		$validator 				= Validator::make($model['attributes'], $model['rules']);

		if ($validator->passes())
		{
			return true;
		}
		else
		{
			$model['errors'] 	= $validator->errors();

			return false;
		}
	}

	public function updating($model)
	{
		if (strtolower($model->chart->tag)=='admin')
		{
			$errors 			= new MessageBag;
			$errors->add('cannotupdate', 'Tidak dapat mengubah autentikasi admin.');

			$model['errors'] 	= $errors;

			return false;
		}
	}
}
