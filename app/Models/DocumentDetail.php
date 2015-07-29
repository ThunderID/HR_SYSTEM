<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_document_id 				: Foreign Key From Pivot PersonDocument, Integer, Required
 * 	template_id 					: Foreign Key From Template, Integer, Required
 * 	item 							: Varchar, 255, Required
 * 	numeric 		 				: Required if text not present
 * 	text 		 					: Required if numeric not present
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	2 Relationships belongsTo 
	{
		PersonDocument
		Template
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class DocumentDetail extends BaseModel {
	
	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonDocumentTrait;
	use \App\Models\Traits\BelongsTo\HasTemplateTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'documents_details';
	
	protected 	$fillable			= 	[
											'template_id' 				,
											'numeric' 					,
											'text' 						,
										];

	protected 	$rules				= 	[
											'template_id'			=> 'required|exists:tmp_templates,id',
											'numeric'				=> 'required_without:text',
											'text'					=> 'required_without:numeric',
										];

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
}
