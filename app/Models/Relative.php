<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	relative_id 					: Foreign Key From Document, Integer, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
 	1 Relationship belongsTo 
	{
		Organisation
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Relative extends BaseModel {
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	
	public 		$timestamps 		= true;

	protected 	$table 				= 	'relatives';
	
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'relativeid' 				=> 'RelativeID', 
											'personid' 					=> 'PersonID', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'relativeid' 				=> 'Could be array or integer', 
											'withattributes' 			=> 'Must be array of relationship',
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
			return $query->whereIn('relatives.id', $variable);
		}
		return $query->where('relatives.id', $variable);
	}
	
	public function scopeRelativeID($query, $variable)
	{
		return $query->where('relative_id', $variable);
	}
}
