<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	workleave_id 					: Foreign Key From Workleave, Integer, Required
 * 	start 		 					: Required, date
 * 	end  		 					: Required, date
 * 	is_default  		 			: Boolean
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	2 Relationships belongsTo 
	{
		Person
		Workleave
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class PersonWorkleave extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\BelongsTo\HasWorkleaveTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				=	'persons_workleaves';

	protected 	$fillable			= 	[
											'workleave_id' 				,
											'start' 					,
											'end' 						,
											'is_default' 				,
										];

	protected 	$rules				= 	[
											'workleave_id'				=> 'required|exists:workleaves,id',
											'start'						=> 'required|date_format:"Y-m-d"',
											'end'						=> 'required|date_format:"Y-m-d"',
											'is_default'				=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'workleaveid' 				=> 'WorkleaveID', 
											'ondate' 					=> 'OnDate', 
											'withattributes' 			=> 'WithAttributes'
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
		return $query->where('id', $variable);
	}

	public function scopePersonID($query, $variable)
	{
		return $query->where('person_id', $variable);
	}

	public function scopeWorkleaveID($query, $variable)
	{
		return $query->where('workleave_id', $variable);
	}

	public function scopeOnDate($query, $variable)
	{
		if(is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				return $query->where('start', '<=', date('Y-m-d', strtotime($variable[1])))
							 ->where('end', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			elseif(!is_null($variable[0]))
			{
				return $query->where('end', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			else
			{
				return $query->where('end', '>=', date('Y'));
			}
		}
		return $query->where('end', '>=', date('Y-m-d', strtotime($variable)));
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
