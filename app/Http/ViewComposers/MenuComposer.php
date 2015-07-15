<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Menu;
use Input, Validator, App, Paginator;

class MenuComposer extends WidgetComposer 
{
	protected function setRules($options)
	{
		$widget_rules['form_url']			= ['url'];									// url for form submit
		$widget_rules['search'] 			= ['array'];								// search: label for search
		$widget_rules['sort'] 				= ['array'];								// sort: label for sort
		$widget_rules['page'] 				= ['required', 'numeric'];					// page: label for page
		$widget_rules['per_page'] 			= ['required', 'numeric', 'max:100'];		// per page: label for per page
		$widget_rules['new'] 				= ['boolean'];								// per page: label for per page

		return $widget_rules;
	}

	protected function setData($options)
	{
		$results 								=  $this->dispatch(new Getting(new Menu, $options['search'], $options['sort'] , (int)$options['page'], (int)$options['per_page'], isset($options['new']) ? $options['new'] : false));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('Menu', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('Menu', $value);
				}
			}

			$widget_data['menu'] 				= null;
			$widget_data['menu-pagination'] 	= null;
		}
		else
		{
			$page 								= json_decode(json_encode($contents->pagination), true);
			$widget_data['menu'] 				= json_decode(json_encode($contents->data), true);
			$widget_data['menu-pagination'] 	= new Paginator($page['total_data'], $page['total_data'], $page['per_page'], $page['page']);
			$widget_data['menu-display'] 		= $page;
		}
		
		return $widget_data;
	}
}