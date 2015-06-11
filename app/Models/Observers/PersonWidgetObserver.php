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
			if(json_decode(json_encode($model['attributes']['query']) , true ) && (!is_null($model['attributes']['field'] && json_decode(json_encode( $model['attributes']['field'])))))
			{
				return true;
			}
			else
			{
				$model['errors'] 		= ['Query dan field harus dalam format json'];

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
		//
		if(isset($model->getDirty()['order']))
		{
			$updates 					= PersonWidget::where('order', $model['attributes']['order'])->get(['id']);

			foreach ($updates as $key => $value) 
			{
				$updated 	 			= PersonWidget::where('id', $value)->update(['order' => $model->getDirty()['order']]);
				if(!$updated)
				{
					$model['errors'] 	= $updated->getError();
					
					return false;
				}
			}
		}
		return true;
	}

	public function deleting($model)
	{
		//
		if($model['attributes']['order']>4)
		{
			$deletes 					= PersonWidget::where('order', '>', $model['attributes']['order'])->get(['id']);
			$order 						= $model['attributes']['order'];

			foreach ($deletes as $key => $value) 
			{
				$updated 	 			= PersonWidget::where('id', $value->id)->update(['order' => $order]);
				if(!$updated)
				{
					$model['errors'] 	= $updated->getError();

					return false;
				}
				$order++;
			}
		}
		return true;
	}
}
