<?php namespace App\Http\ViewComposers\Organisation;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use Input, Validator, App;

class OrganisationComposer extends \App\Http\ViewComposers\WidgetComposer {

	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['required', 'url'];				// url for form submit
		$this->widget_rules['org_id'] 			= ['required', 'alpha_dash'];		// user_id: username/email/etc for user identification
		$this->widget_rules['org_id_label'] 	= ['required'];						// user_id: label for user_id
	}

	protected function setData()
	{
		
	}
}