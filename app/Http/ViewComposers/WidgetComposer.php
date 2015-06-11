<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use Input, App, Validator;

abstract class WidgetComposer {

	protected $widget_options;
	protected $widget_data;
	protected $widget_rules;
	protected $widget_errors;

	function __construct() 
	{
		$this->widget_rules = [];
		$this->widget_data = [];
		$this->widget_errors = new MessageBag();
	}

	protected abstract function setRules();
	protected abstract function setData();

	protected function ValidateOptions()
	{
		// ------------------------------------------------------------------------------------
		// WIDGET OPTIONS
		// ------------------------------------------------------------------------------------
		$validator  = Validator::make($this->widget_options, $this->widget_rules);
		if ($validator->fails())
		{
			foreach ($validator->messages()->toArray() as $k => $v)
			{
				$this->widget_errors->add($k, head($v));
			}
		}

	}

	public function compose(View $view)
	{
		// ------------------------------------------------------------------------------------
		// INIT WIDGET OPTIONS
		// ------------------------------------------------------------------------------------
		$this->widget_options = ($view->widget_options ? $view->widget_options : []);
		
		// ------------------------------------------------------------------------------------
		// INIT WIDGET DATA
		// ------------------------------------------------------------------------------------
		$this->widget_data = ($view->widget_data ? $view->widget_data : []);
		foreach ($this->widget_options as $k => $v)
		{
			$this->widget_data[$k] = $v;
		}

		// ------------------------------------------------------------------------------------
		// INIT WIDGET ERRORS
		// ------------------------------------------------------------------------------------
		if (isset($view->widget_errors) && $view->widget_errors->count())
		{
			$this->widget_errors = $view->widget_errors;
		}
		
		// ------------------------------------------------------------------------------------
		// INIT WIDGET NAME
		// ------------------------------------------------------------------------------------
		$this->widget_data['widget_name'] = $view->getName();

		// ------------------------------------------------------------------------------------
		// PROCESS
		// ------------------------------------------------------------------------------------
		$this->setRules();
		$this->ValidateOptions();

		if (!$this->widget_errors->count())
		{
			$this->setData();
		}

		// ------------------------------------------------------------------------------------
		// PASS TO WIDGET
		// ------------------------------------------------------------------------------------
		$view->with('widget_data', $this->widget_data);
		$view->with('widget_errors', $this->widget_errors);
	}
}