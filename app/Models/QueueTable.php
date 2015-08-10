<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	queue_id 						: Foreign Key From Chart, Integer, Required
 * 	queue_type 						: Model Class, Required, Auto
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
* 	//this package
 	1 Relationship morphto 
	{
		Calendar
	}

 * 	//other package
 	1 Relationship belongsTo 
	{
		Chart
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class QueueTable extends BaseModel {

	use SoftDeletes;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'queues_table';

	protected 	$fillable			= 	[
											'queue_id' 					,
										];

	protected 	$rules				= 	[
											'queue_id'					=> 'required|exists:tmp_queues,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'queueid' 					=> 'QueueID', 

											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'queueid' 					=> 'Could be array or integer', 

											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at', 'queue_id'];

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
			return $query->whereIn('queues_table.id', $variable);
		}
		return $query->where('queues_table.id', $variable);
	}
}
