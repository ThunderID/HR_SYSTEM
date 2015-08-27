<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Required, Integer, FK from Person
 * 	record_log_id 					: Required, Integer, FK from Other Table
 * 	record_log_type 			 	: Required, morph model
 * 	name 			 				: Required, max : 255
 * 	level 			 				: integer
 * 	notes 							:
 * 	old_attributes 					:
 * 	new_attributes 					:
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Person
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class RecordLog extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'record_logs';

	protected 	$fillable			= 	[
											'person_id' 						,
											'record_log_id' 					,
											'record_log_type' 					,
											'action' 							,
											'name' 								,
											'level' 							,
											'notes' 							,
											'old_attributes' 					,
											'new_attributes' 					,
										];

	protected 	$rules				= 	[
											'person_id' 						=> 'numeric',
											'name' 								=> 'required',
											'level' 							=> 'numeric',
											'action' 							=> 'required|in:save,delete,restore',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'personid' 							=> 'PersonID', 
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'personid' 							=> 'Could be array or integer', 
										];

	public $sortable 				= 	['person_id', 'created_at'];

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
			return $query->whereIn('record_logs.id', $variable);
		}
		return $query->where('record_logs.id', $variable);
	}
}
