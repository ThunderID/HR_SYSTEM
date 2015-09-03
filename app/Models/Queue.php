<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	created_by 						: Foreign Key From Person, Integer, Required
 * 	process_name 		 			: Required : max 255
 * 	process_option 		 			: Required : max 255
 * 	parameter 		 				: Required
 * 	total_process 		 			: Required : numeric
 * 	task_per_process 		 		: Required : numeric
 * 	process_number 		 			: Required : numeric
 * 	total_task 		 				: Required : numeric
 * 	message 		 				: Required
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

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Queue extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\HasMany\HasQueueMorphsTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'tmp_queues';

	protected 	$fillable			= 	[
											'created_by' 				,
											'process_name' 				,
											'process_option' 			,
											'parameter' 				,
											'total_process' 			,
											'task_per_process' 			,
											'process_number' 			,
											'total_task' 				,
											'message' 					,
										];

	protected 	$rules				= 	[
											'process_name'				=> 'required',
											'process_option'			=> 'max:255',
											'parameter'					=> 'required',
											'total_process'				=> 'required|numeric',
											'task_per_process'			=> 'required|numeric',
											'process_number'			=> 'required|numeric',
											'total_task'				=> 'required|numeric',
											'message'					=> 'required',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'createdbyid' 				=> 'CreatedByID', 
											'running' 					=> 'Running', 
											'complete' 					=> 'Complete', 
											'processname' 				=> 'ProcessName', 
											'processoption' 			=> 'ProcessOption', 

											'withattributes' 			=> 'WithAttributes'
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'running' 					=> 'Null', 
											'complete' 					=> 'Null', 
											'createdbyid' 				=> 'Must be id of person', 
											'processname' 				=> 'Must be string', 
											'processoption' 			=> 'Must be string', 

											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at'];

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
			return $query->whereIn('tmp_queues.id', $variable);
		}
		return $query->where('tmp_queues.id', $variable);
	}

	public function scopeProcessName($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('process_name', $variable);
		}

		return $query->where('process_name', $variable);
	}

	public function scopeProcessOption($query, $variable)
	{
		return $query->where('process_option', $variable);
	}

	public function scopeRunning($query, $variable)
	{
		return $query->whereRaw('`process_number` < `total_process`');
	}

	public function scopeComplete($query, $variable)
	{
		return $query->whereRaw('`process_number` = `total_process`');
	}

	public function scopeCreatedByID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('tmp_queues.created_by', $variable);
		}
		return $query->where('tmp_queues.created_by', $variable);
	}
}
