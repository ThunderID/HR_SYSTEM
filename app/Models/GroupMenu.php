<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	tmp_auth_group_id 				: Required, Integer, FK from Auth Group
 * 	menu_id 						: Required, Integer, FK from Menu
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	2 Relationships belongsTo
	{
		Menu
		AuthGroup
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class GroupMenu extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasMenuTrait;
	use \App\Models\Traits\BelongsTo\HasAuthGroupTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_groups_menus';

	protected 	$fillable			=	[
											'tmp_auth_group_id' 				,
										];

	protected 	$rules				= 	[
											'tmp_auth_group_id' 				=> 'required|exists:tmp_auth_groups,id',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'authgroupid' 						=> 'AuthGroupID', 
											'menuid' 							=> 'MenuID', 

											'withattributes'					=> 'WithAttributes',
											'withtrashed' 						=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'authgroupid' 						=> 'Could be array or integer', 
											'menuid' 							=> 'Could be array or integer', 

											'withattributes' 					=> 'Must be array of relationship',
											'withtrashed' 						=> 'Must be true',
										];

	public $sortable 				= 	['tmp_auth_group_id'];

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
			return $query->whereIn('tmp_groups_menus.id', $variable);
		}
		return $query->where('tmp_groups_menus.id', $variable);
	}
}
