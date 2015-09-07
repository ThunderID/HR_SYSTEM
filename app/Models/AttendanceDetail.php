<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	attendance_log_id 				: Foreign Key From Attendance Log, Integer, Required
 * 	person_workleave_id 			: Foreign Key From Person Workleave, Integer, Required
 * 	person_document_id 				: Foreign Key From Person Document, Integer, Required
 * 	person_workleave_type 			: Model Class, Required, Auto
 * 	person_document_type 			: Model Class, Required, Auto
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		AttendanceLog
	}

 	2 Relationships morphTo 
	{
		PersonWorkleave
		PersonDocument
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class AttendanceDetail extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasAttendanceLogTrait;
	use \App\Models\Traits\MorphTo\HasPersonWorkleaveTrait;
	use \App\Models\Traits\MorphTo\HasPersonDocumentTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'attendance_details';
	
	protected 	$fillable			= 	[
											'attendance_log_id' 		,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'attendance_log_id' 		=> 'required|exists:attendance_logs,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personworkleaveid' 		=> 'PersonWorkleaveID', 
											'persondocumentid' 			=> 'PersonDocumentID', 
											'notpersondocumentid' 		=> 'NotPersonDocumentID', 
											'organisationid' 			=> 'OrganisationID', 
											'processlogondate' 			=> 'ProcessLogOnDate', 

											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personworkleaveid' 		=> 'Could be array or integer', 
											'persondocumentid' 			=> 'Could be array or integer', 
											'notpersondocumentid' 		=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 

											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
											'processlogondate' 			=> 'Could be array or string (date)', 
										];

	public $sortable 				= 	['created_at', 'person_workleave_id', 'person_document_id'];

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
			return $query->whereIn('attendance_details.id', $variable);
		}
		return $query->where('attendance_details.id', $variable);
	}
	
	public function scopePersonWorkleaveID($query, $variable)
	{
		return $query->where('person_workleave_id', $variable);
	}

	public function scopePersonDocumentID($query, $variable)
	{
		return $query->where('person_document_id', $variable);
	}

	public function scopeNotPersonDocumentID($query, $variable)
	{
		return $query->where('person_document_id', '<>', $variable);
	}
}
