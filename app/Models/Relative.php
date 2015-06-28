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
	use \App\Models\Traits\BelongsTo\HasRelativeTrait;
	
	public 		$timestamps 		= true;

	protected 	$table 				= 	'relatives';

	protected 	$fillable			= 	[
											'relative_id' 				,
											'relationship' 				,
										];

	protected 	$rules				= 	[
											'relative_id' 				=> 'required|exists:persons,id',
											'relationship' 				=> 'required|in:spouse,parent,child,partner',
										];
	
	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'relativeid' 				=> 'RelativeID', 
											'personid' 					=> 'PersonID', 

											'relationship' 				=> 'Relationship',
											'relativeorganisationid' 	=> 'RelativeOrganisationID',
											'defaultcontact' 			=> 'DefaultContact',

											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'relativeid' 				=> 'Could be array or integer', 

											'relativeorganisationid' 	=> 'Could be array or integer',
											'defaultcontact' 			=> 'Must be true or false',
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'relationship'];


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

	public function scopeRelationship($query, $variable)
	{
		return $query->where('relationship', $variable);
	}
}
