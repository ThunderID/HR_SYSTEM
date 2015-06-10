<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth;


class Counting extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($model, $query, $sortby, $page = 1, $per_page = 10)
	{
		$this->validate_sortable($model);
		$this->validate_searchable($model);
		$this->max_per_page = 100;

		// searchable
		$this->searchable = $model->searchable;

		// sortable
		$this->sortable = $model->sortable;

		// model
		$this->model = $model;
		$this->validate_model($model);

		// query
		$this->query = $query;
		$this->validate_query($query);

		// sort
		$this->sortby = $sortby;
		$this->validate_sortby($sortby);

		// page
		$this->page = $page;
		$this->validate_page($page);

		// per page
		$this->per_page = $per_page;
		$this->validate_perpage($per_page);

	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{		
		return $this->count();
	}

	protected function count()
	{
		$response = new APIResponse(['total' => $this->build_query()->count()], null, 1);
		return $response->toJson();
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	private function build_query()
	{
		$model = $this->model;
		
		// generate query 
		if (!empty($this->query))
		{
			foreach ($this->query as $field => $value)
			{
				$model = call_user_func([$model,$this->searchable[strtolower($field)]], $value);
			}
		}

		return $model;
	}

	private function validate_query($query)
	{
		// format of query
		if (!is_array($query))
		{
			throw new Exception("Query: Must be an array ([field => value])", 1);
		}

		// query must be in searchable
		foreach ($query as $field => $value)
		{
			if (!array_key_exists(strtolower($field), ($this->searchable)))
			{
				throw new Exception($field . ": Is not searchable", 2);
			}
		}
	}

	/**
	 * validate sortby
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_sortby($sortby)
	{
		// format of sortby
		if (!is_array($sortby))
		{
			throw new Exception("SortBy: Must be an array ([field => asc|desc])", 3);
		}

		// sortby must be in sortable
		foreach ($sortby as $field => $value)
		{
			if (!in_array(strtolower($field), $this->sortable))
			{
				throw new Exception($field . ": Is not sortable", 4);
			}

			if (!str_is('asc', $value) && !str_is('desc', $value))
			{
				throw new Exception("Sort mode must be either asc or desc", 9);
			}
		}
	}

	/**
	 * validate page
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_page($page)
	{
		if (!is_integer($page) || $page < 1)
		{
			throw new Exception("Page must be an integer greater than 0", 5);
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	private function validate_perpage($per_page)
	{
		if (!is_integer($per_page) || $per_page < 1 || $per_page > 100)
		{
			throw new Exception("Page must be an integer between 1 and 100", 6);
		}
	}

	private function validate_searchable($model)
	{
		if (!isset($model->searchable))
		{
			throw new Exception("Searchable field has not been set", 10);
		}

		// check if function exists
		foreach ($model->searchable as $field => $func)
		{
			if (!method_exists($model, 'scope'.$func))
			{
				throw new Exception("Function for searching $field ($func) does not exist", 11);
			}
		}
	}

	private function validate_sortable($model)
	{
		if (!isset($model->sortable))
		{
			throw new Exception("Sortable field has not been set", 12);
		}
	}

	private function validate_model()
	{
		if (!isset($this->model))
		{
			throw new Exception("Model does not exist", 13);
		}
	}

}
