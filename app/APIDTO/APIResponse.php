<?php namespace APP\APIDTO;

use Exception;

class APIResponse {

	protected $attributes;
	
	function __construct($data = null, $errors = null, $page_info = []) 
	{
		// data
		if (!is_array($data) && !is_null($data))
		{
			throw new Exception("Results must be an array", 1);
		}
		$this->attributes['data'] = $data;

		// errors
		if ($errors)
		{
			if (!is_array($errors))
			{
				throw new Exception("Results must be an array", 1);
			}
			$this->attributes['meta']['success'] = false;
			$this->attributes['meta']['errors']	= $errors;
		}
		else
		{
			$this->attributes['meta']['success'] = true;
		}

		// 
		if ($page_info)
		{
			$this->attributes['pagination']= $page_info;
		}
	}

	function toJson()
	{
		$results['meta'] = $this->attributes['meta'];
		$results['data'] = $this->attributes['data'];
		$results['pagination'] = $this->attributes['pagination'];
		return json_encode($results);
	}
}