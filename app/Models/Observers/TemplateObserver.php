<?php namespace App\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class TemplateObserver 
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

	public function deleting($model)
	{
		if($model->Details()->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus template dokumen yang berkaitan dengan karyawan'];

			return false;
		}

		return true;
	}
}
