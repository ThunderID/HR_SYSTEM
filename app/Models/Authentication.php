<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	menu_id 						: Required, Integer, FK from Menu
 * 	chart_id 						: Required, Integer, FK from Chart
 * 	create 			 				: Boolean
 * 	read 			 				: Boolean
 * 	update 			 				: Boolean
 * 	delete 			 				: Boolean
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//this package
	1 Relationship belongsTo
	{
		Menu
	}

	//other package
	1 Relationship belongsTo
	{
		Chart
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Authentication extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasChartTrait;
	use \App\Models\Traits\BelongsTo\HasMenuTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'authentications';

	protected 	$fillable			=	[
											'chart_id' 							,
											'create' 							,
											'read' 								,
											'update' 							,
											'delete' 							,
										];

	protected 	$rules				= 	[
											'chart_id' 							=> 'required|exists:charts,id',
											'create'							=> 'boolean',
											'read'								=> 'boolean',
											'update'							=> 'boolean',
											'delete'							=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'chartid' 							=> 'ChartID', 
											'menuid' 							=> 'MenuID', 
											'email' 							=> 'Email', 
											'access' 							=> 'Access', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $sortable 				= 	['chart_id', 'created_at'];

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
		if(is_array($variable))
		{
			return $query->whereIn('chart_id', $variable);
		}

		return $query->where('chart_id', $variable);
	}

	public function scopeMenuID($query, $variable)
	{
		return $query->where('menu_id', $variable);
	}

	public function scopeAccess($query, $variable)
	{
		if(is_null($variable))
		{
			return $query;
		}
		if(in_array($variable, ['create', 'read', 'update', 'delete']))
		{
			return $query->where($variable, true);
		}
		
		return $query;
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
