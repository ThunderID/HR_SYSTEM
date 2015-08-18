<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	work_id 						: Foreign Key From Work, Integer, Required
 * 	workleave_id 					: Foreign Key From Workleave, Integer, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
* 	//this package
 	1 Relationship belongsTo 
	{
		Workleave
	}

 * 	//other package
 	1 Relationship belongsTo 
	{
		Work
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class FollowWorkleave extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasWorkleaveTrait;
	use \App\Models\Traits\BelongsTo\HasWorkTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'follow_workleaves';

	protected 	$fillable			= 	[
											'work_id' 					,
										];

	protected 	$rules				= 	[
											'work_id'					=> 'required|exists:works,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'workid' 					=> 'WorkID', 
											'workleaveid' 				=> 'WorkleaveID', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'workid' 					=> 'Could be array or integer', 
											'workleaveid' 				=> 'Could be array or integer', 
											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at', 'work_id'];

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
			return $query->whereIn('follow_workleaves.id', $variable);
		}
		return $query->where('follow_workleaves.id', $variable);
	}
}
