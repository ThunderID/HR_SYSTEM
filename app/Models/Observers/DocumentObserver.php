<?php namespace App\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class DocumentObserver 
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
		//
		if($model->persons->count() || $model->templates->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus dokumen yang berkaitan dengan karyawan atau yang memiliki template'];

			return false;
		}

		return true;
	}
}
