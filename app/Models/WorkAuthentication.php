<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	tmp_auth_group_id 				: Required, Integer, FK from Auth Group
 * 	organisation_id 				: Required, Integer, FK from Organisation
 * 	work_id 						: Required, Integer, FK from Work
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	3 Relationships belongsTo
	{
		AuthGroup
		Organisation
		Work
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class WorkAuthentication extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasAuthGroupTrait;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\BelongsTo\HasWorkTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'works_authentications';

	protected 	$fillable			=	[
											'tmp_auth_group_id' 				,
											'organisation_id' 					,
										];

	protected 	$rules				= 	[
											'tmp_auth_group_id' 				=> 'required|exists:tmp_auth_groups,id',
											'organisation_id' 					=> 'required|exists:organisations,id',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'workid' 							=> 'WorkID', 
											'organisationid' 					=> 'OrganisationID', 
											'authgroupid' 						=> 'AuthGroupID', 
											'withattributes'					=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'workid' 							=> 'Could be array or integer', 
											'organisationid' 					=> 'Could be array or integer', 
											'authgroupid' 						=> 'Could be array or integer', 

											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['organisation_id', 'work_id', 'tmp_auth_group_id'];

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
			return $query->whereIn('works_authentications.id', $variable);
		}
		return $query->where('works_authentications.id', $variable);
	}
}
