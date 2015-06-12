<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	application_id 		 			: FK from table Application, Required
 * 	name 	 						: Varchar, 255, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//this package
	1 Relationship hasMany 
	{
		Authentications
	}
	1 Relationship belongsTo 
	{
		Application
	}

	//other package
	1 Relationship belongsToMany 
	{
		Charts
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Menu extends BaseModel {

	use \App\Models\Traits\HasMany\HasAuthenticationsTrait;
	use \App\Models\Traits\BelongsToMany\HasAuthenticationChartsTrait;
	use \App\Models\Traits\BelongsTo\HasApplicationTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_menus';
	
	protected 	$fillable			= 	[
											'name' 							,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 							=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'applicationid' 			=> 'ApplicationID', 
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'applicationid' 			=> 'Could be array or integer', 
											'name' 						=> 'Must be string', 
											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['name', 'menu', 'created_at', 'tmp_applications.id'];

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
