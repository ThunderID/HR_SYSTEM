<?php namespace App\Models\Traits\BelongsToMany;

trait HasDocumentDetailsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDocumentDetailsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function DocumentDetails()
	{
		return $this->belongsToMany('App\Models\PersonDocument', 'documents_details', 'template_id', 'person_document_id')
					->withPivot('id','text', 'numeric');
	}
}