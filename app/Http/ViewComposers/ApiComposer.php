<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Api;
use Input, Validator, App, Paginator;

class ApiComposer extends WidgetComposer 
{
	protected function setRules($options)
	{
		$widget_rules['form_url']			= ['url'];									// url for form submit
		$widget_rules['branch_id'] 			= ['required', 'alpha_dash'];				// organisation_id: filter organisation
		$widget_rules['search'] 			= ['array'];								// search: label for search
		$widget_rules['sort'] 				= ['array'];								// sort: label for sort
		$widget_rules['page'] 				= ['required', 'numeric'];					// page: label for page
		$widget_rules['per_page'] 			= ['required', 'numeric', 'max:100'];		// per page: label for per page
		$widget_rules['identifier'] 		= ['required', 'numeric'];					// identifier

		return $widget_rules;
	}

	protected function setData($options)
	{
		$widget_data['search']['branchid'] 	= $options['branch_id'];

		$results 								=  $this->dispatch(new Getting(new Api, $options['search'], $options['sort'] , $options['page'], $options['per_page']));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('Api', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('Api', $value);
				}
			}

			$widget_data['api'] 				= null;
			$widget_data['api-pagination'] 		= null;
		}
		else
		{
			$page 								= json_decode(json_encode($contents->pagination), true);
			$widget_data['api'] 				= json_decode(json_encode($contents->data), true);
			$widget_data['api-pagination'] 		= new Paginator($page['total_data'], $page['total_data'], $page['per_page'], $page['page']);
			$widget_data['api-display'] 		= $page;
		}
		
		return $widget_data;
	}
}