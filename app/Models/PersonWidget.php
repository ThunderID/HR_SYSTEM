<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign key from Person
 * 	type 	 						: Required in list or table or stat
 * 	widget 	 						: Required, Varchar, 255, Required
 * 	search 	 	 					: Required
 * 	sort 	 	 					: Required
 * 	page 	 						: Required, int
 * 	per_page 	 					: Required, int
 * 	row 		 					: Required, tiny int
 * 	col 		 					: Required, tiny int
 * 	is_active 		 				: Boolean
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
	//other package
	1 Relationship belongsTo
	{
		Person
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class PersonWidget extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 'person_widgets';

	protected 	$fillable			= 	[
											'type' 						,
											'widget' 					,
											'search' 					,
											'sort' 						,
											'page' 						,
											'per_page' 					,
											'row' 						,
											'col' 						,
											'is_active' 				,
										];
	protected	$dates 				= ['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'type' 						=> 'required|in:table,list,stat',
											'widget' 					=> 'required|max:255',
											'search' 					=> 'required',
											'sort' 						=> 'required',
											'page' 						=> 'required|numeric',
											'per_page' 					=> 'required|numeric',
											'row' 						=> 'required|numeric',
											'col' 						=> 'required|numeric',
											'is_active' 				=> 'boolean',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 
											'widget' 					=> 'Widget', 
											'type' 						=> 'Type', 
											'withattributes' 			=> 'WithAttributes',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'widget' 					=> 'Must be string', 
											'type' 						=> 'Must be string', 
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['col', 'row', 'created_at'];

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
			return $query->whereIn('person_widgets.id', $variable);
		}
		return $query->where('person_widgets.id', $variable);
	}
	
	public function scopeWidget($query, $variable)
	{
		return $query->where('widget', 'like' ,'%'.$variable.'%');
	}

	public function scopeType($query, $variable)
	{
		return $query->where('type', $variable);
	}
}
