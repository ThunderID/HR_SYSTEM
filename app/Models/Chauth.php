<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	application_id 					: Required, Integer, FK from Application
 * 	chart_id 						: Required, Integer, FK from Chart
 * 	is_create 		 				: bool
 * 	is_read 		 				: bool
 * 	is_update 		 				: bool
 * 	is_delete 		 				: bool
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Chart
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Chauth extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasChartTrait;
	use \App\Models\Traits\BelongsTo\HasApplicationTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'charts_applications';
	protected 	$fillable			= [
										'application_id' 						,
										'is_create' 							,
										'is_read' 								,
										'is_update' 							,
										'is_delete' 							,
									];
	protected 	$rules				= [
										'application_id' 						=> 'required|exists:applications,id',
										'is_create' 							=> 'boolean',
										'is_read' 								=> 'boolean',
										'is_update' 							=> 'boolean',
										'is_delete' 							=> 'boolean',
									];
	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'chartid' 							=> 'ChartID', 
											'withattributes' 					=> 'WithAttributes',
										];
	public $sortable 				= ['chart_id', 'created_at'];

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
		return $query->where('id', $variable);
	}

	public function scopeChartID($query, $variable)
	{
		return $query->where('chart_id', $variable);
	}

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}

		return $query->with($variable);
	}
}
