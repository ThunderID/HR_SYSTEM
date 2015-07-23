<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	application_id 		 			: FK from table Application, Required
 * 	name 	 						: Varchar, 255, Required
 * 	tag 	 						: Varchar, 255, Required
 * 	description 	 				:
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//this package
	1 Relationship belongsTo 
	{
		Application
	}

	//other package
	1 Relationship belongsToMany 
	{
		AuthGroups
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Menu extends BaseModel {

	use \App\Models\Traits\BelongsToMany\HasAuthGroupsTrait;
	use \App\Models\Traits\BelongsTo\HasApplicationTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_menus';
	
	protected 	$fillable			= 	[
											'name' 							,
											'tag' 							,
											'description' 					,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 							=> 'required|max:255',
											'tag' 							=> 'max:255',
										];

	public $searchable 				= 	[
											'id' 							=> 'ID', 
											'applicationid' 				=> 'ApplicationID', 
											'hasapplication' 				=> 'HasApplication', 
											'name' 							=> 'Name', 
											'level' 						=> 'Level', 
											'withattributes' 				=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'applicationid' 			=> 'Could be array or integer', 
											'hasapplication' 			=> 'Null', 
											'name' 						=> 'Must be string', 
											'level' 					=> 'Must be integer', 
											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['name', 'tag', 'created_at', 'tmp_applications.id', 'application_id'];

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
			return $query->whereIn('tmp_menus.id', $variable);
		}
		return $query->where('tmp_menus.id', $variable);
	}
	
	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopeLevel($query, $variable)
	{
		if((int)$variable)
		{
			return $query->where('tmp_menus.id', '>=', $variable);
		}

		return $query;
	}
}
