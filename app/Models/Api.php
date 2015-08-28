<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	branch_id 						: Required, Integer, FK from Branch
 * 	client 			 				: Required, unique, max : 255
 * 	secret 			 				: Required, max : 255
 * 	workstation_address 			: Required, max : 255
 * 	workstation_name 			 	: Required, max : 255
 * 	tr_version 			 			: Required, max : 255
 * 	is_active 			 			: Boolean
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Branch
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Api extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasBranchTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'apis';

	protected 	$fillable			= 	[
											'client' 							,
											'secret' 							,
											'workstation_address' 				,
											'workstation_name' 					,
											'tr_version' 						,
											'is_active' 						,
										];

	protected 	$rules				= 	[
											'client' 							=> 'required',
											'secret' 							=> 'required',
											'workstation_address' 				=> 'required',
											'workstation_name' 					=> 'required',
											'is_active' 						=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'branchid' 							=> 'BranchID', 
											
											'client' 							=> 'Client', 
											'secret' 							=> 'Secret', 
											'workstationaddress' 				=> 'WorkstationAddress', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'branchid' 							=> 'Could be array or integer', 
											
											'client' 							=> 'Must be string', 
											'secret' 							=> 'Must be string', 
											'macadress' 						=> 'Must be string', 
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['branch_id', 'created_at', 'client', 'workstation_name'];

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
			return $query->whereIn('apis.id', $variable);
		}
		return $query->where('apis.id', $variable);
	}
	
	public function scopeWorkstationAddress($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('apis.workstation_address', $variable);
		}
		return $query->where('apis.workstation_address', $variable);
	}

	public function scopeClient($query, $variable)
	{
		return $query->where('client', $variable);
	}

	public function scopeSecret($query, $variable)
	{
		return $query->where('secret', $variable);
	}
}
