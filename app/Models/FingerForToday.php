<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	branch_id 						: Foreign Key From Person, Integer, Required
 *	finger							: Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Branch
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class FingerForToday extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasBranchTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'branch_fingers';

	protected 	$fillable			= 	[
											'finger' 					,
										];

	protected 	$rules				= 	[
											'finger'					=> 'required|in:left_thumb,left_index_finger,left_middle_finger,left_ring_finger,left_little_finger,right_thumb,right_index_finger,right_middle_finger,right_ring_finger,right_little_finger';
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'stillwork' 				=> 'StillWork', 
											'updatedat' 				=> 'UpdatedAt', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $sortable 				= ['created_at'];

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
		return $query->where('id', $variable);
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->where('branch_id', $variable);
	}

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}
		return $query->with($variable);
	}
}
