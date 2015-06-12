<?php namespace App\Http\ViewComposers\Common;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use Input, Validator, App;

class LoginFormComposer extends WidgetComposer {

	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['required', 'url'];				// url for form submit
		$this->widget_rules['user_id'] 			= ['required', 'alpha_dash'];		// user_id: username/email/etc for user identification
		$this->widget_rules['user_id_label'] 	= ['required'];						// user_id: label for user_id
	}

	protected function setData()
	{
		
	}
}