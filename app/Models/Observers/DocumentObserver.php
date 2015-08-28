<?php namespace App\Models\Observers;

use \Validator;
use \App\Models\Template;

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
		if($model->persons->count())
		{
			$model['errors'] 	= ['Tidak dapat menghapus dokumen yang berkaitan dengan karyawan atau yang memiliki template'];

			return false;
		}
		else
		{
			foreach ($model->templates as $key => $value) 
			{
				$template 	 			= new Template;
				$delete 				= $template->find($value['id']);

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
