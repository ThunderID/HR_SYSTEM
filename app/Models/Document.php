<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	name 							: Varchar, 255, Required
 * 	tag 							: Varchar, 255, Required
 * 	is_required 					: Boolean
 * 	template 		 				: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship hasMany 
	{
		Templates
	}

	//other package
	1 Relationship belongsToMany 
	{
		Persons
	}

	1 Relationship belongsTo 
	{
		Organisation
	}
 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Document extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\BelongsToMany\HasPersonDocumentsTrait;
	use \App\Models\Traits\HasMany\HasTemplatesTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'tmp_documents';

	protected 	$fillable			= 	[
											'name' 							,
											'tag' 							,
											'is_required' 					,
											'template' 						,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'name' 					=> 'required|max:255',
											'tag' 					=> 'required|max:255',
											'is_required' 			=> 'boolean',
											'template'				=> '',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'organisationid' 			=> 'OrganisationID', 

											'name' 						=> 'Name', 
											'tag' 						=> 'Tag', 
											'grouptag'	 				=> 'GroupTag',

											'checkcreate' 				=> 'CheckCreate',
											'isrequired' 				=> 'Required', 
											
											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
										];
										
	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 

											'name' 						=> 'Must be string', 
											'tag' 						=> 'Must be string', 
											'grouptag'	 				=> 'Null',

											'checkcreate' 				=> 'Could be array or string (time)',
											'isrequired' 				=> 'Must be true or false', 
											
											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
										];

	public $sortable 				= 	['id', 'name', 'is_required', 'created_at', 'tag'];

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
			return $query->whereIn('tmp_documents.id', $variable);
		}
		return $query->where('tmp_documents.id', $variable);
	}
	
	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopeTag($query, $variable)
	{
		return $query->where('tag', $variable);
	}

	public function scopeRequired($query, $variable)
	{
		return $query->where('is_required', $variable);
	}

	public function scopeGroupTag($query, $variable)
	{
		return $query->groupby('tag');
	}

	public function scopeCheckCreate($query, $variable)
	{
		if(!is_array($variable))
		{
			$days 				= new DateTime($variable);

			return $query->where('created_at', '>=', $days->format('Y-m-d'));
		}
		return $query->where('created_at', '>=', $variable[0])
					->where('created_at', '<=', $variable[1]);
	}
}
