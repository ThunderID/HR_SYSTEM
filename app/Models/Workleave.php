<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	name 		 					: Required max 255
 * 	quota 			 				: Required, Integer
 * 	status  		 				: Required, in CI, CB, CN
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

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Workleave extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\HasMany\HasPersonWorkleavesTrait;
	use \App\Models\Traits\BelongsToMany\HasFollowedWorksTrait;

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
											'status'					=> 'required|in:CI,CN',
											'is_active'					=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'organisationid' 			=> 'OrganisationID', 
											'name' 						=> 'Name', 
											'active' 					=> 'Active', 
											'status' 					=> 'Status', 
											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 
											'name' 						=> 'Must be string', 
											'active' 					=> 'Must be true or false', 
											'status' 					=> 'Must be string', 
											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
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

	public function scopeQuota($query, $variable)
	{
		return $query->where('quota', $variable);
	}

	public function scopeActive($query, $variable)
	{
		return $query->where('is_active', $variable);
	}

	public function scopeStatus($query, $variable)
	{
			if(is_array($variable))
		{
			return $query->whereIn('tmp_workleaves.status', $variable);
		}
		return $query->where('tmp_workleaves.status', $variable);
	}
}
