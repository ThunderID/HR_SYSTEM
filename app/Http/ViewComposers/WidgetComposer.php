<?php namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\MessageBag;
use Input, App, Validator;

abstract class WidgetComposer 
{
	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	protected $widget_composers; //hold all composer list for current views
	protected $widget_options;
	protected $widget_data;
	protected $widget_rules;
	protected $widget_errors;

	function __construct()
	{
		$this->composer_name 						= basename(str_replace('\\', '/', get_class($this)));
		$this->widget_rules							= [];
		$this->widget_errors						= new MessageBag();
		$this->widget_data[$this->composer_name] 	= [];
	}

	protected abstract function setRules($widget_options);
	protected abstract function setData($widget_options);

	protected function ValidateOptions()
	{
		// ------------------------------------------------------------------------------------
		// WIDGET OPTIONS
		// ------------------------------------------------------------------------------------
		foreach ($this->widget_options as $k => $widget_options)
		{
			if (!$this->widget_rules[$k])
			{
				$this->widget_rules[$k] 			= [];
			}
			
			$validator 								= Validator::make($widget_options, $this->widget_rules[$k]);
			if ($validator->fails())
			{
				foreach ($validator->messages()->toArray() as $k2 => $v2)
				{
					$this->widget_errors->add($k2, head($v2));
				}
			}
		}
	}
	public function compose(View $view)
	{

		// ------------------------------------------------------------------------------------
		// INIT WIDGET OPTIONS []
		// ------------------------------------------------------------------------------------
		if (is_array($view->widget_options))
		{
			foreach ($view->widget_options as $k => $v)
			{
				if (!is_array($v))
				{
					$this->widget_errors->add($k, "Options [$k] must be an array");
				}
			}
			$this->widget_options 					= $view->widget_options;
		}
		else
		{
			$this->widget_options 					= [];
		}

		// ------------------------------------------------------------------------------------
		// INIT WIDGET DATA
		// ------------------------------------------------------------------------------------
		$this->widget_data 							= ($this->widget_options ? $this->widget_options : []);

		// ------------------------------------------------------------------------------------
		// PROCESS
		// ------------------------------------------------------------------------------------
		if (!$this->widget_errors->count())
		{
			foreach ($this->widget_options as $k => $v)
			{
				$rules 								= $this->setRules($v);
				$this->widget_rules[$k] 			= $rules;
			}
			
			$this->ValidateOptions();
			if (!$this->widget_errors->count())
			{
				foreach ($this->widget_options as $k => $v)
				{
					$data 							= $this->setData($v);
					if (!is_array($data))
					{
						$data 						= [];
					}
					foreach ($data as $k2 => $v2)
					{
						$this->widget_data[$k][$k2] = $v2;
					}
				}
			}
		}

		// ------------------------------------------------------------------------------------
		// PASS TO WIDGET
		// ------------------------------------------------------------------------------------
		$view->with($this->composer_name, [
				'widget_data' 						=> $this->widget_data,
				'widget_errors'						=> $this->widget_errors
		]);

		// widget composer
		$this->widget_composers 					= ($view->widget_composers ? $view->widget_composers : []);
		$this->widget_composers[] 					= $this->composer_name;
		$view->with('widget_composers', $this->widget_composers);

		// widget name
		$view->with('widget_name', $view->getName());

		// calculate tota error
		$widget_error_count 						= $view->widget_error_count;
		$widget_error_count+= $this->widget_errors->count();
		$view->with('widget_error_count', $widget_error_count);
	}
}