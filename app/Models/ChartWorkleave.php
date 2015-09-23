<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	chart_id 						: Foreign Key From Work, Integer, Required
 * 	workleave_id 					: Foreign Key From Workleave, Integer, Required
 * 	rules 							: Text
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
* 	//this package
 	1 Relationship belongsTo 
	{
		Workleave
	}

 * 	//other package
 	1 Relationship belongsTo 
	{
		Work
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class ChartWorkleave extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasWorkleaveTrait;
	use \App\Models\Traits\BelongsTo\HasChartTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'charts_workleaves';

	protected 	$fillable			= 	[
											'chart_id' 					,
											'rules' 					,
										];

	protected 	$rules				= 	[
											'chart_id'					=> 'required|exists:charts,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'chartid' 					=> 'ChartID', 
											'workleaveid' 				=> 'WorkleaveID', 
											'status' 					=> 'WorkleaveStatus', 
											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'chartid' 					=> 'Could be array or integer', 
											'workleaveid' 				=> 'Could be array or integer', 
											'status' 					=> 'Could be array or string', 
											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
										];

	public $sortable 				= 	['created_at', 'chart_id'];

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
			return $query->whereIn('charts_workleaves.id', $variable);
		}
		return $query->where('charts_workleaves.id', $variable);
	}
}
