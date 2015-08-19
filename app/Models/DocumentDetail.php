<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_document_id 				: Foreign Key From Pivot PersonDocument, Integer, Required
 * 	template_id 					: Foreign Key From Template, Integer, Required
 * 	on 			 					: Required if text, string, numeric not present
 * 	numeric 		 				: Required if string, date, text not present
 * 	string 			 				: Required if date, numeric, text not present
 * 	text 		 					: Required if numeric, date, string not present
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
											'on' 						,
											'numeric' 					,
											'string' 					,
											'text' 						,
										];

	protected 	$rules				= 	[
											'template_id'			=> 'required|exists:tmp_templates,id',
											'on'					=> 'required_without_all:text,string,numeric',
											'numeric'				=> 'required_without_all:text,string,on',
											'string'				=> 'required_without_all:text,on,numeric',
											'text'					=> 'required_without_all:numeric,on,string',
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
