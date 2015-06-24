<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	document_id 					: Foreign Key From Document, Integer, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship hasMany 
	{
		Details
	}

	1 Relationship belongsTo 
	{
		Document
	}

 * 	//other package
	1 Relationship belongsTo 
	{
		Person
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class PersonDocument extends BaseModel {
	
	use \App\Models\Traits\HasMany\HasDetailsTrait;
	use \App\Models\Traits\BelongsTo\HasDocumentTrait;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'persons_documents';

	protected 	$fillable			= 	[
											'document_id',
										];

	protected 	$rules				= 	[
											'document_id'				=> 'required|exists:tmp_documents,id',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'documentid' 				=> 'DocumentID', 
											'personid' 					=> 'PersonID', 
											
											'documenttag' 				=> 'DocumentTag', 

											'branchid' 					=> 'BranchID', 
											'currentwork' 				=> 'CurrentWork',
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'documentid' 				=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'documenttag' 				=> 'Must be string', 
											'branchid' 					=> 'Could be array or integer', 
											'currentwork' 				=> 'Could be null or integer of ID',
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'person_id'];

	protected $appends				= 	['document_number'];

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
	
	public function getDocumentNumberAttribute($value)
	{
		if(isset($this->getRelations()['document']))
		{
			$letter = date('Y/m/d', strtotime($this->created_at)).'/'.Str::slug($this->document->name).'/'.$this->person_id.'/'.$this->id;
			return $letter;
		}
		return '/';
	}

	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('persons_documents.id', $variable);
		}
		return $query->where('persons_documents.id', $variable);
	}
	
}
