<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	process_log_id 					: Required, Integer, FK from Process Log
 * 	total_active 			 		: Required, numeric
 * 	total_idle 			 			: Required, numeric
 * 	total_idle_1 			 		: Required, numeric
 * 	total_idle_2 			 		: Required, numeric
 * 	total_idle_3 			 		: Required, numeric
 * 	frequency_idle_1 			 	: Required, numeric
 * 	frequency_idle_2 			 	: Required, numeric
 * 	frequency_idle_3 			 	: Required, numeric
 * 	break_idle		 				: Numeric
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Processlog
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class IdleLog extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasProcessLogTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'idle_logs';

	protected 	$fillable			= 	[
											'total_active' 						,
											'total_idle' 						,
											'total_idle_1' 						,
											'total_idle_2' 						,
											'total_idle_3' 						,
											'break_idle' 						,
											'frequency_idle_1' 					,
											'frequency_idle_2' 					,
											'frequency_idle_3' 					,
										];

	protected 	$rules				= 	[
											'total_active' 						=> 'required|numeric',
											'total_idle' 						=> 'required|numeric',
											'total_idle_1' 						=> 'required|numeric',
											'total_idle_2' 						=> 'required|numeric',
											'total_idle_3' 						=> 'required|numeric',
											'break_idle'						=> 'numeric',
											'frequency_idle_1' 					=> 'required|numeric',
											'frequency_idle_2' 					=> 'required|numeric',
											'frequency_idle_3' 					=> 'required|numeric',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'processlogid' 						=> 'ProcessLogID', 
											'processlogondate' 					=> 'ProcessLogOnDate', 
											'totalprocesslogondate' 			=> 'TotalProcessLogOnDate', 
											'organisationid' 					=> 'OrganisationID', 
											'personid' 							=> 'ProcessLogPersonID', 
											
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'processlogid' 						=> 'Could be array or integer', 
											'organisationid' 					=> 'Could be array or integer', 
											'personid' 							=> 'Could be array or integer', 
											
											'withattributes' 					=> 'Must be array of relationship',
											'processlogondate' 					=> 'Could be array or string (date)', 
											'totalprocesslogondate' 			=> 'Could be array or string (date)', 
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
			return $query->whereIn('idle_logs.id', $variable);
		}
		return $query->where('idle_logs.id', $variable);
	}

	public function scopeTotalProcessLogOndate($query, $variable)
	{
		if(isset($variable) && is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				$start 	= date('Y-m-d', strtotime($variable[0]));
				$end 	= date('Y-m-d', strtotime($variable[1]));
			}
			elseif(!is_null($variable[0]))
			{
				$start 	= date('Y-m-d', strtotime($variable[0]));
				$end 	= date('Y-m-d', strtotime($variable[1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable))
		{
			$start 	= date('Y-m-d', strtotime($variable));
			$end 	= date('Y-m-d', strtotime($variable));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}

		return $query
					->leftjoin('process_logs', function ($join) use($start, $end) {
							$join->on('process_logs.id', '=', 'idle_logs.process_log_id')
								->where('process_logs.on', '>=', $start)
								->where('process_logs.on', '<=', $end)
								->wherenull('process_logs.deleted_at')
							;
						})
					->selectraw('IFNULL(sum(total_idle), 0) as total_idle')
					->selectraw('IFNULL(sum(total_active), 0) as total_active')
					;
	}
}
