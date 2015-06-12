<?php namespace App\Http\ViewComposers\Organisation;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Getting;
use App\Models\Organisation;
use Input, Validator, App;



class OrganisationComposer extends \App\Http\ViewComposers\WidgetComposer 
{
	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	protected function setRules()
	{
		$this->widget_rules['form_url']			= ['required', 'url'];				// url for form submit
		$this->widget_rules['org_id'] 			= ['required', 'alpha_dash'];		// user_id: username/email/etc for user identification
		$this->widget_rules['org_id_label'] 	= ['required'];						// user_id: label for user_id
	}

	protected function setData()
	{
		$results 								=  $this->dispatch(new Getting(new Organisation, [], [] , 1, 100));

		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}
		
		$this->widget_data['organisation'] 		= json_decode(json_encode($contents->data), true);
	}
}