<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	ip 							: Required, Max : 255
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class IPWhitelist extends BaseModel {

	use SoftDeletes;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'tmp_ip_whitelists';

	protected 	$fillable			=	[
											'ip' 								,
										];

	protected 	$rules				= 	[
											'ip' 								=> 'required|max:255',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'ip' 								=> 'ip', 

											'withattributes'					=> 'WithAttributes',
											'withtrashed' 						=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'ip' 								=> 'Must be string', 
											'level' 							=> 'Must be integer', 

											'withattributes' 					=> 'Must be array of relationship',
											'withtrashed' 						=> 'Must be true',
										];

	public $sortable 				= 	['ip', 'id'];

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
			return $query->whereIn('tmp_ip_whitelists.id', $variable);
		}
		return $query->where('tmp_ip_whitelists.id', $variable);
	}

	public function scopeIP($query, $variable)
	{
		return $query->where('ip', $variable);
	}
}
