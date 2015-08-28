<?php namespace App\Models\Observers;

use \Validator;
use \App\Models\Detail;

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
		else
		{
			foreach ($model->details as $key => $value) 
			{
				$detail 	 			= new Detail;
				$delete 				= $detail->find($value['id']);

				if($delete && !$delete->delete())
				{
					$model['errors']	= $delete->getError();
					
					return false;
				}
			}
		}

		return true;
	}
}
