<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	name 		 					: Required max 255
 * 	quota 			 				: Required, Integer
 * 	status  		 				: Required, in offer, annual, special, confirmed
 * 	is_active 			 			: Boolean
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Organisation
	}

 	1 Relationship belongsToMany 
	{
		Persons
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Workleave extends BaseModel {

	// use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				=	'tmp_workleaves';

	protected 	$fillable			= 	[
											'name' 						,
											'quota' 					,
											'status' 					,
											'is_active' 				,
										];

	protected 	$rules				= 	[
											'name'						=> 'required|max:255',
											'quota'						=> 'required|numeric',
											'status'					=> 'required|in:annual,special',
											'is_active'					=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'organisationid' 			=> 'OrganisationID', 
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 
											'name' 						=> 'Must be string', 
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'name'];

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

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('tmp_workleaves.id', $variable);
		}
		return $query->where('tmp_workleaves.id', $variable);
	}
	
	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like', '%'.$variable.'%');
	}

}
