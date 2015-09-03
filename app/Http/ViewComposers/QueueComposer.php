<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Queue;
use Input, Validator, App, Paginator;

class QueueComposer extends WidgetComposer 
{
	protected function setRules($options)
	{
		$widget_rules['form_url'] 				= ['requiredWithout', 'url'];				// url for form submit
		// $widget_rules['organisation_id'] 		= ['required', 'alpha_dash'];				// organisation_id: filter organisation
		$widget_rules['search'] 				= ['array'];								// search: label for search
		$widget_rules['sort'] 					= ['array'];								// sort: label for sort
		$widget_rules['page'] 					= ['required', 'numeric'];					// page: label for page
		$widget_rules['per_page'] 				= ['required', 'numeric', 'max:100'];		// per page: label for per page
		$widget_rules['new'] 					= ['boolean'];								// per page: label for per page

		return $widget_rules;
	}

	protected function setData($options)
	{
		// $options['search']['organisationid'] 	= $options['organisation_id'];

		$results 								=  $this->dispatch(new Getting(new Queue, $options['search'], $options['sort'] , (int)$options['page'], (int)$options['per_page'], isset($options['new']) ? $options['new'] : false));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('Queue', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('Queue', $value);
				}
			}
			$widget_data['queue'] 				= null;
			$widget_data['queue-pagination'] 	= null;
		}
		else
		{
			$page 								= json_decode(json_encode($contents->pagination), true);
			$widget_data['queue'] 				= json_decode(json_encode($contents->data), true);
			$widget_data['queue-pagination'] 	= new Paginator($page['total_data'], $page['total_data'], $page['per_page'], $page['page']);
			$widget_data['queue-pagination']->setPath(route('hr.policies.index'));
			$widget_data['queue-display'] 		= $page;
		}

		return $widget_data;
	}
}