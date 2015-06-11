<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	document_id 					: Foreign Key From Document, Integer, Required
 * 	field 							: Varchar, 255, Required
 * 	type 		 					: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship belongsTo 
	{
		Document
	}

	1 Relationship belongsToMany
	{
		DocumentDetails
	}

	1 Relationship hasMany
	{
		Details
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Template extends BaseModel {
	
	use \App\Models\Traits\BelongsTo\HasDocumentTrait;
	use \App\Models\Traits\BelongsToMany\HasDocumentDetailsTrait;
	use \App\Models\Traits\HasMany\HasDetailsTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'templates';
	protected 	$fillable			= 	[
											'field' 							,
											'type' 								,
										];

	protected 	$rules				= 	[
											'field' 							=> 'required|max:255',
											'type'								=> 'required|in:numeric,text,string,date',
										];

	public $searchable 				= 	[
											'id' 								=> 'ID', 
											'documentid'	 					=> 'DocumentID', 
											'organisationid'	 				=> 'OrganisationID', 
											'field' 							=> 'Field', 
											'withattributes' 					=> 'WithAttributes'
										];
										
	public $sortable 				=	['id', 'field', 'created_at'];
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

	public function scopeField($query, $variable)
	{
		return $query->where('field', 'like' ,'%'.$variable.'%');
	}
}
