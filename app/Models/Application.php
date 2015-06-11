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

	protected 	$table 				= 'applications';
	protected 	$fillable			= [
										'name' 							,
									];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];
	protected 	$rules				= [
										'name' 							=> 'required|max:255',
									];
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'chartid' 					=> 'ChartID', 
											
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes',
										];
	public $sortable 				= ['name', 'created_at', 'applications.id'];

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

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}
}
