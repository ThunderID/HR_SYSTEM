<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	branch_id 						: Foreign Key From Branch, Integer, Required
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	branch_type 					: Model Class, Required, Auto
 * 	person_type 					: Model Class, Required, Auto
 * 	item 		 					: Required max 255
 * 	value 		 					: Required
 * 	is_default 		 				: bool
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	2 Relationships morphTo 
	{
		Branch
		Person
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Contact extends BaseModel {

	use \App\Models\Traits\MorphTo\HasBranchTrait;
	use \App\Models\Traits\MorphTo\HasPersonTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'contacts';
	
	protected 	$fillable			= 	[
											'item' 						,
											'value' 					,
											'is_default' 				,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'item' 						=> 'required|max:255',
											'value' 					=> 'required',
											'is_default' 				=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'branchid' 					=> 'BranchID', 
											'personid' 					=> 'PersonID', 

											'groupitem' 				=> 'GroupItem', 

											'item' 						=> 'Item', 
											'value' 					=> 'Value', 
											'default' 					=> 'Default', 

											'messageservices' 			=> 'MessageServices', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'branchid' 					=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 

											'groupitem'	 				=> 'Null',
											'item' 						=> 'Could be array or string', 
											'value' 					=> 'Could be array or string', 
											'default' 					=> 'Must be true or false', 

											'messageservices' 			=> 'No Need', 
											'withattributes' 			=> 'Must be array of relationship'
										];

	public $sortable 				= 	['created_at', 'is_default', 'item', 'value'];

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
			return $query->whereIn('contacts.id', $variable);
		}
		return $query->where('contacts.id', $variable);
	}
	
	public function scopeBranchID($query, $variable)
	{
		return $query->where('branch_id', $variable);
	}

	public function scopePersonID($query, $variable)
	{
		return $query->where('person_id', $variable);
	}


	public function scopeGroupItem($query, $variable)
	{
		return $query->groupBy('item');
	}

	public function scopeItem($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('item', $variable);
		}
		return $query->where('item', $variable);
	}

	public function scopeValue($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('value', $variable);
		}
		return $query->where('value', $variable);
	}

	public function scopeMessageServices($query, $variable)
	{
		return $query->whereNotIn('item', ['email', 'address' , 'phone']);
	}

	public function scopeDefault($query, $variable)
	{
		return $query->where('is_default', $variable);
	}
}
