<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 *	left_thumb						: 
 *	left_index_finger				: 
 *	left_middle_finger				: 
 *	left_ring_finger				: 
 *	left_little_finger				: 
 *	right_thumb						: 
 *	right_index_finger				: 
 *	right_middle_finger				: 
 *	right_ring_finger				: 
 *	right_little_finger				: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Person
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Finger extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'fingers';

	protected 	$fillable			= 	[
											'left_thumb' 				,
											'left_index_finger' 		,
											'left_middle_finger' 		,
											'left_ring_finger' 			,
											'left_little_finger' 		,
											'right_thumb' 				,
											'right_index_finger' 		,
											'right_middle_finger' 		,
											'right_ring_finger' 		,
											'right_little_finger' 		,
										];

	protected 	$rules				= 	[
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

	public function scopePersonID($query, $variable)
	{
		return $query->where('person_id', $variable);
	}

	public function scopeUpdatedAt($query, $variable)
	{
		return $query->where('updated_at', '>=', $variable);
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
