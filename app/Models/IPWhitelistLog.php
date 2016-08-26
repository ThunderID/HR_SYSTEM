<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	email 		 					: max 255
 * 	name 		 					: max 255
 * 	on 		 						: Datetime
 * 	pc 			 					: max 255
 * 	message 						: text
 * 	ip 		 						: max 255
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	1 Relationship belongsTo 
	{
		Organisation
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class IPWhitelistLog extends BaseModel {

	use SoftDeletes;

	public 		$timestamps 		= true;

	protected 	$table 				= 'whitelist_logs';

	protected 	$fillable			= 	[
											'email' 					,
											'name' 						,
											'on' 						,
											'pc' 						,
											'message' 					,
											'ip' 						,
										];

	protected 	$rules				= 	[
											'email'						=> 'max:255',
											'name'						=> 'max:255',
											'on'						=> 'date_format:"Y-m-d H:i:s"',
											'pc'						=> 'max:255',
											'ip'						=> 'max:255',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'name' 						=> 'Name', 
											'withattributes' 			=> 'WithAttributes'
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'name' 						=> 'Must be string', 
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
			return $query->whereIn('whitelist_logs.id', $variable);
		}
		return $query->where('whitelist_logs.id', $variable);
	}
	
	public function scopeName($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('name', $variable);
		}

		return $query->where('name', $variable);
	}

	public function scopeOnDate($query, $variable)
	{
		if(is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				return $query->where('on', '<=', date('Y-m-d', strtotime($variable[1])))
							 ->where('on', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			elseif(!is_null($variable[0]))
			{
				return $query->where('on', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			else
			{
				return $query->where('on', '>=', date('Y-m-d'));
			}
		}
		return $query->where('on', '>=', date('Y-m-d', strtotime($variable)));
	}

}
