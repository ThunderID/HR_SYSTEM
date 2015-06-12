<?php namespace App\Http\ViewComposers\Organisation;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use Input, Validator, App;



class OrganisationComposer extends \App\Http\ViewComposers\WidgetComposer 
{
	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['required', 'url'];				// url for form submit
		$this->widget_rules['org_id'] 			= ['required', 'alpha_dash'];		// user_id: username/email/etc for user identification
		$this->widget_rules['org_id_label'] 	= ['required'];						// user_id: label for user_id
		$this->widget_rules['org_search'] 		= ['array'];						// org_search: label for org_search
		$this->widget_rules['org_sort'] 		= ['array'];						// org_sort: label for org_sort
	}

	protected function setData()
	{
		$results 								=  $this->dispatch(new Getting(new Organisation, $this->widget_data['org_search'], $this->widget_data['org_sort'] , 1, 100));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			foreach ($contents->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$this->widget_errors->add('Organisation', $value2);
					}
				}
				else
				{
					$this->widget_errors->add('Organisation', $value);
				}
			}

			$this->widget_data['organisation'] 	= [];
		}
		else
		{
			$this->widget_data['organisation'] 	= json_decode(json_encode($contents->data), true);
		}
		
	}
}