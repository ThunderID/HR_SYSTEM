<?php namespace App\Models;


/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Required, Integer, FK from Organisation
 * 	created_by 						: Required, Integer, FK from Person
 * 	start 			 				: Required, date
 * 	margin_bottom_idle 			 	: Required, numeric
 * 	idle_1 			 				: Required, numeric
 * 	idle_2 			 				: Required, numeric
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Organisation
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class SettingIdle extends BaseModel {

	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'setting_idles';

	protected 	$fillable			= 	[
											'created_by' 						,
											'start' 							,
											'margin_bottom_idle' 				,
											'idle_1' 							,
											'idle_2' 							,
										];

	protected 	$rules				= 	[
											'created_by' 						=> 'required|exists:persons,id',
											'start' 							=> 'required|date_format:"Y-m-d"',
											'margin_bottom_idle' 				=> 'required|numeric',
											'idle_1' 							=> 'required|numeric',
											'idle_2' 							=> 'required|numeric',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'organisationid' 					=> 'OrganisationID', 
											
											'ondate' 							=> 'OnDate', 
											'withattributes' 					=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 								=> 'Could be array or integer', 
											'organisationid' 					=> 'Could be array or integer', 
											
											'ondate' 							=> 'Must be string or array of date', 
											'withattributes' 					=> 'Must be array of relationship',
										];

	public $sortable 				= 	['start', 'created_at'];

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
			return $query->whereIn('setting_idles.id', $variable);
		}
		return $query->where('setting_idles.id', $variable);
	}
	
	public function scopeOnDate($query, $variable)
	{
		if(is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				return $query->where('start', '<=', date('Y-m-d', strtotime($variable[1])))
							 ->where('start', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			elseif(!is_null($variable[0]))
			{
				return $query->where('start', '<=', date('Y-m-d', strtotime($variable[0])));
			}
			else
			{
				return $query->where('start', '<=', date('Y-m-d', strtotime('now')));
			}
		}

		return $query->where('start', '<=', date('Y-m-d', strtotime($variable)));
	}

	public function CreatedBy()
	{
		return $this->belongsTo('App\Models\Person', 'created_by', 'id');
	}
}
