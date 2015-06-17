<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 	 						: Varchar, 255, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship hasMany 
	{
		Menus
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Application extends BaseModel {

	use \App\Models\Traits\HasMany\HasMenusTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_applications';

	protected 	$fillable			= 	[
											'name' 							,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 								=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'chartid' 							=> 'ChartID', 
											
											'name' 								=> 'Name', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'chartid' 							=> 'Could be array or integer', 
											
											'anme' 								=> 'Must be string', 
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['name', 'created_at', 'tmp_applications.id'];

	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/
	/**
	 * return errors
	 *
	 * @return MessageBag
	 * @author 
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/

	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('tmp_applications.id', $variable);
		}
		return $query->where('tmp_applications.id', $variable);
	}
	
	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}
}
