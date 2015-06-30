<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	menu_id 						: Required, Integer, FK from Menu
 * 	chart_id 						: Required, Integer, FK from Chart
 * 	is create 			 			: Boolean
 * 	is read 			 			: Boolean
 * 	is update 			 			: Boolean
 * 	is delete 			 			: Boolean
 * 	settings 			 			: Text
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
											'is_create' 						,
											'is_read' 							,
											'is_update' 						,
											'is_delete' 						,
											'settings' 							,
										];

	protected 	$rules				= 	[
											'chart_id' 							=> 'required|exists:charts,id',
											'is_create'							=> 'boolean',
											'is_read'							=> 'boolean',
											'is_update'							=> 'boolean',
											'is_delete'							=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'chartid' 							=> 'ChartID', 
											'menuid' 							=> 'MenuID', 
											
											'email' 							=> 'Email', 
											'access' 							=> 'Access', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'chartid' 							=> 'Could be array or integer', 
											'menuid' 							=> 'Could be array or integer', 
											
											'email' 							=> 'Must be string', 
											'access' 							=> 'String in create, read, update, delete or null for all access', 
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['chart_id', 'created_at', 'menu_id'];

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
			return $query->whereIn('authentications.id', $variable);
		}
		return $query->where('authentications.id', $variable);
	}
	
	public function scopeAccess($query, $variable)
	{
		if(is_null($variable))
		{
			return $query;
		}
		if(in_array($variable, ['is_create', 'is_read', 'is_update', 'is_delete']))
		{
			return $query->where($variable, true);
		}
		
		return $query;
	}
}
