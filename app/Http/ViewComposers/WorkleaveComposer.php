<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Workleave;
use Input, Validator, App, Paginator;

class WorkleaveComposer extends WidgetComposer 
{
	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['url'];									// url for form submit
		$this->widget_rules['organisation_id'] 	= ['required', 'alpha_dash'];				// organisation_id: filter organisation
		$this->widget_rules['search'] 			= ['array'];								// search: label for search
		$this->widget_rules['sort'] 			= ['array'];								// sort: label for sort
		$this->widget_rules['page'] 			= ['required', 'numeric'];					// page: label for page
		$this->widget_rules['per_page'] 		= ['required', 'numeric', 'max:100'];		// per page: label for per page
		$this->widget_rules['identifier'] 		= ['required', 'numeric'];					// identifier
	}

	protected function setData()
	{
		$this->widget_data['search']['organisationid'] 	= $this->widget_data['organisation_id'];

		$results 								=  $this->dispatch(new Getting(new Workleave, $this->widget_data['search'], $this->widget_data['sort'] , $this->widget_data['page'], $this->widget_data['per_page']));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('Workleave', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('Workleave', $value);
				}
			}

			$this->widget_data['workleave-'.$this->widget_data['identifier']] 				= null;
			$this->widget_data['workleave-pagination-'.$this->widget_data['identifier']] 	= null;
		}
		else
		{
			$page 																		= json_decode(json_encode($contents->pagination), true);
			$this->widget_data['workleave-'.$this->widget_data['identifier']] 			= json_decode(json_encode($contents->data), true);
			$this->widget_data['workleave-pagination-'.$this->widget_data['identifier']] = new Paginator($page['total_data'], $page['total_data'], $page['per_page'], $page['page']);
			$this->widget_data['workleave-pagination-'.$this->widget_data['identifier']]->setPath(route('hr.workleaves.index'));
			$this->widget_data['workleave-display-'.$this->widget_data['identifier']] 	= $page;
		}
	}
}