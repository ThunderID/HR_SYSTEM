<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	work_id 						: Foreign Key From Work, Integer, Required
 * 	workleave_id 					: Foreign Key From Workleave, Integer, Required
 * 	person_workleave_id 			: Foreign Key From Person Workleave, Integer, Required
 * 	created_by 						: Foreign Key From Person, Integer, Required
 * 	name 		 					: Required, max 255
 * 	notes 		 					:
 * 	start 		 					: Required, date
 * 	end  		 					: Required, date
 * 	quota  		 					: Required, numeric
 * 	status  		 				: Required, OFFER, CB, CN, CI, CONFIRMED
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

	1 Relationship morphMany 
	{
		WorkleaveAttendanceDetails
	}
 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class PersonWorkleave extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\BelongsTo\HasWorkleaveTrait;
	use \App\Models\Traits\BelongsTo\HasWorkTrait;
	use \App\Models\Traits\BelongsTo\HasPersonWorkleaveTrait;
	use \App\Models\Traits\HasMany\HasPersonWorkleavesTrait;
	use \App\Models\Traits\MorphMany\HasWorkleaveAttendanceDetailsTrait;
	use \App\Models\Traits\MorphTo\HasQueueMorphTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				=	'person_workleaves';

	protected 	$fillable			= 	[
											'work_id' 					,
											'workleave_id' 				,
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
											'person_workleave_id'		=> '',
											'workleave_id'				=> '',
											'created_by'				=> '',
											'name'						=> 'required|max:255',
											'start'						=> 'required|date_format:"Y-m-d"',
											'end'						=> 'required_if:status,CB|date_format:"Y-m-d"',
											'quota'						=> 'required|numeric',
											'status'					=> 'required|in:OFFER,CB,CN,CI',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'organisationid' 			=> 'PersonOrganisationID', 
											'workleaveid' 				=> 'WorkleaveID',
											'parentid' 					=> 'ParentID',
											'name' 						=> 'Name',
											'status' 					=> 'Status',
											'quota' 					=> 'Quota',
											'sumquota' 					=> 'SumQuota',
											 
											'ondate' 					=> 'OnDate', 
											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
											'totalondate' 				=> 'TotalOnDate', 
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 
											'workleaveid' 				=> 'Could be array or integer', 
											'parentid' 					=> 'Could be array or integer', 
											'name' 						=> 'Must be string',
											'status' 					=> 'Could be array or string',
											'quota' 					=> 'Must be true or not true',
											'sumquota' 					=> 'Must be true',

											'ondate' 					=> 'Could be array or string (date)', 
											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
											'totalondate' 				=> 'Could be array or string (date)', 
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
			return $query->whereIn('person_workleaves.workleave_id', $variable);
		}
		return $query->where('person_workleaves.workleave_id', $variable);
	}

	public function scopeParentID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('person_workleaves.person_workleave_id', $variable);
		}
		return $query->where('person_workleaves.person_workleave_id', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
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

	public function scopeQuota($query, $variable)
	{
		if($variable)
		{
			return $query->where('quota', '>', 0);
		}
		return $query->where('quota', '<', 0);
	}

	public function scopeSumQuota($query, $variable)
	{
		return $query->selectraw('person_workleaves.*')
					 ->selectraw('sum(quota) as total_quota')
					 ;
	}

	public function scopeTotalOndate($query, $variable)
	{
		return $query->selectraw('count(id) as total_person')
					->ondate($variable);
	}
}
