<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	created_by 						: Foreign Key From Person, Integer, Required
 * 	name 		 					: Required max 255
 * 	on 		 						: Required, Datetime
 * 	last_input_time 		 		: Nullable, Datetime
 * 	app_version 		 			: max 255
 * 	ip 		 						: max 255
 * 	pc 			 					: Required max 255
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

class Log extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'logs';

	protected 	$fillable			= 	[
											'person_id' 				,
											'created_by' 				,
											'name' 						,
											'on' 						,
											'last_input_time' 			,
											'pc' 						,
											'ip' 						,
											'app_version' 				,
										];

	protected 	$rules				= 	[
											'person_id'					=> 'exists:persons,id',
											'created_by'				=> 'exists:persons,id',
											'name'						=> 'required|max:255',
											'on'						=> 'required|date_format:"Y-m-d H:i:s"',
											'last_input_time'			=> 'date_format:"Y-m-d H:i:s"',
											'pc'						=> 'required|max:255',
											'app_version'				=> 'max:255',
											'ip'						=> 'max:255',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'name' 						=> 'Name', 
											'ondate' 					=> 'OnDate', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'name' 						=> 'Must be string', 
											'ondate' 					=> 'Could be array or string (date)', 
											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at', 'on'];

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
			return $query->whereIn('logs.id', $variable);
		}
		return $query->where('logs.id', $variable);
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
				return  $query->where('on', '<=', date('Y-m-d', strtotime($variable[1])))
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
