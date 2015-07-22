<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 							: Required, Max : 255
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsToMany
	{
		Menu
	}

	1 Relationship hasMany
	{
		WorkAuthentication
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class AuthGroup extends BaseModel {

	use \App\Models\Traits\BelongsToMany\HasMenusTrait;
	use \App\Models\Traits\HasMany\HasWorkAuthenticationsTrait;
	use \App\Models\Traits\HasMany\HasGroupMenusTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_auth_groups';

	protected 	$fillable			=	[
											'name' 								,
										];

	protected 	$rules				= 	[
											'name' 								=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'name' 								=> 'Name', 
											'level' 							=> 'Level', 

											'withattributes'					=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'name' 								=> 'Must be string', 
											'level' 							=> 'Must be integer', 

											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['name', 'id'];

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
			return $query->whereIn('tmp_auth_groups.id', $variable);
		}
		return $query->where('tmp_auth_groups.id', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopeLevel($query, $variable)
	{
		if((int)$variable)
		{
			return $query->where('tmp_auth_groups.id', '>=', $variable);
		}

		return $query;
	}
}
