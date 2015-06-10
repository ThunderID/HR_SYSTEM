<?php namespace App\Models\Observers;

use \Validator;

/* ----------------------------------------------------------------------
 * Event:
 * 	Saving						
 * 	Deleting						
 * ---------------------------------------------------------------------- */

class PersonDocumentObserver 
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
		if($model->document->is_required)
		{
			$model['errors'] 	= ['Tidak dapat menghapus dokumen yang wajib ada'];

			return false;
		}

		return true;
	}
}
