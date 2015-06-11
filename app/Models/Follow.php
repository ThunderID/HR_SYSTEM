<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	chart_id 						: Foreign Key From Chart, Integer, Required
 * 	calendar_id 					: Foreign Key From Calendar, Integer, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
* 	//this package
 	1 Relationship belongsTo 
	{
		Calendar
	}

 * 	//other package
 	1 Relationship belongsTo 
	{
		Chart
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Follow extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasCalendarTrait;
	use \App\Models\Traits\BelongsTo\HasChartTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'follows';

	protected 	$fillable			= 	[
											'chart_id' 					,
										];

	protected 	$rules				= 	[
											'chart_id'					=> 'required|exists:charts,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'chartid' 					=> 'ChartID', 
											'calendarid' 				=> 'CalendarID', 
											'withattributes' 			=> 'WithAttributes'
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
}
