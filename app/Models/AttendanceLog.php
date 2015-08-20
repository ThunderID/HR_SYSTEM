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
 * 	margin_start 		 			: Double
 * 	margin_end 		 				: Double
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
	use \App\Models\Traits\HasMany\HasAttendanceDetailsTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'attendance_logs';
	
	protected 	$fillable			= 	[
											'actual_status' 					,
											'modified_status' 					,
											'count_status' 						,
											'tolerance_time' 					,
											'margin_start' 						,
											'margin_end' 						,
											'notes' 							,
											'modified_at' 						,
											'modified_by' 						,
											'settlement_at' 					,
										];

	protected 	$rules				= 	[
											'actual_status' 					=> 'required|max:255',
											'modified_status' 					=> 'max:255',
											'count_status' 						=> 'required|numeric',
											'tolerance_time' 					=> 'numeric',
											'modified_at' 						=> 'date_format:"Y-m-d H:i:s"',
											'settlement_at' 					=> 'date_format:"Y-m-d H:i:s"',
											'margin_start'						=> 'numeric',
											'margin_end'						=> 'numeric',
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

	public function scopeActualStatus($query, $variable)
	{
		return $query->where('actual_status', $variable);
	}

	public function scopeGlobalSanction($query, $variable)
	{
		if(isset($variable['on']) && is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable['on']))
		{
			$start 	= date('Y-m-d', strtotime($variable['on']));
			$end 	= date('Y-m-d', strtotime($variable['on']));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}

		if(isset($variable['personid']))
		{
			$personid 			= $variable['personid'];
		}
		else
		{
			$personid 			= null;
		}

		$query =  $query->selectraw('attendance_logs.*')
					->wherenotin('actual_status', ['HB', 'L'])->wherenotin('modified_status', ['HD', 'SS', 'SL', 'CN', 'CB', 'CI', 'DN', 'L'])
					->wheredoesnthave('attendancedetails', function($q){$q;})
					->join('process_logs', function ($join) use($start, $end, $personid) {
							$join->on('attendance_logs.process_log_id', '=', 'process_logs.id')
								->where('process_logs.on', '>=', $start)
								->where('process_logs.on', '<=', $end)
								->where('process_logs.person_id', '=', $personid)
								->wherenull('process_logs.deleted_at')
							;
						})
					->orderBy('updated_at', 'desc')
					;

		return $query->groupBy('attendance_logs.process_log_id')
					;
	}
}
