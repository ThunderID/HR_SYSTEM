<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	process_log_id 					: Required, Integer, FK from Process Log
 * 	settlement_by 			 		: Required, Integer, FK from Person
 * 	modified_by 			 		: Required, Integer, FK from Person
 * 	actual_status 			 		: Required, max : 255
 * 	modified_status 			 	: Required, max : 255
 * 	count_status 			 		: Required, double
 * 	tolerance_time 			 		: Required, double
 * 	notes 			 				: Required, text
 * 	modified_at 			 		: Required, datetime
 * 	settlement_at 			 		: Required, datetime
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	2 Relationships belongsTo
	{
		Person
		Processlog
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class AttendanceLog extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasProcessLogTrait;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'attendance_logs';

	protected 	$fillable			= 	[
											'actual_status' 					,
											'modified_status' 					,
											'count_status' 						,
											'tolerance_time' 					,
											'notes' 							,
											'modified_at' 						,
											'settlement_at' 					,
										];

	protected 	$rules				= 	[
											'actual_status' 					=> 'required|max:255',
											'modified_status' 					=> 'required|max:255',
											'count_status' 						=> 'required|numeric',
											'tolerance_time' 					=> 'required|numeric',
											'notes' 							=> 'required',
											'modified_at' 						=> 'required|date_format:"Y-m-d H:i:s"',
											'settlement_at' 					=> 'required|date_format:"Y-m-d H:i:s"',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'processlogid' 						=> 'ProcessLogID', 
											
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'processlogid' 						=> 'Could be array or integer', 
											
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['process_log_id', 'created_at'];

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
			return $query->whereIn('attendance_logs.id', $variable);
		}
		return $query->where('attendance_logs.id', $variable);
	}
}
