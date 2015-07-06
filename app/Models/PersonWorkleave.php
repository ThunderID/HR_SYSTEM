<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	work_id 						: Foreign Key From Work, Integer, Required
 * 	person_workleave_id 			: Foreign Key From Person Workleave, Integer, Required
 * 	created_by 						: Foreign Key From Person, Integer, Required
 * 	name 		 					: Required, max 255
 * 	notes 		 					:
 * 	start 		 					: Required, date
 * 	end  		 					: Required, date
 * 	quota  		 					: Required, numeric
 * 	status  		 				: Required, in offer, annual, special, confirmed
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
	use \App\Models\Traits\BelongsTo\HasPersonWorkleaveTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				=	'person_workleaves';

	protected 	$fillable			= 	[
											'work_id' 					,
											'person_workleave_id' 		,
											'created_by' 				,
											'name' 						,
											'notes' 					,
											'start' 					,
											'end' 						,
											'quota' 					,
											'status' 					,
										];

	protected 	$rules				= 	[
											'work_id'					=> 'required|exists:works,id',
											'person_workleave_id'		=> 'required_if:status,confirmed',
											'created_by'				=> 'required|exists:persons,id',
											'name'						=> 'required|max:255',
											'start'						=> 'required|date_format:"Y-m-d"',
											'end'						=> 'required|date_format:"Y-m-d"',
											'quota'						=> 'required|numeric',
											'status'					=> 'required|in:offer,annual,special,confirmed',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'workleaveid' 				=> 'WorkleaveID',
											'status' 					=> 'Status',
											 
											'ondate' 					=> 'OnDate', 
											'withattributes' 			=> 'WithAttributes'
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'workleaveid' 				=> 'Could be array or integer', 
											'status' 					=> 'Could be array or string',

											'ondate' 					=> 'Could be array or string (date)', 
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'name', 'start', 'end'];

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
			return $query->whereIn('person_workleaves.id', $variable);
		}
		return $query->where('person_workleaves.id', $variable);
	}

	public function scopeStatus($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('person_workleaves.status', $variable);
		}
		return $query->where('person_workleaves.status', $variable);
	}

	public function scopeWorkleaveID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('person_workleaves.person_workleave_id', $variable);
		}
		return $query->where('person_workleaves.person_workleave_id', $variable);
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
}
