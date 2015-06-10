<?php namespace App\Models\Traits\BelongsToMany;

trait HasDocumentDetailTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDocumentDetailTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function PersonDocument()
	{
		return $this->belongsToMany('App\Models\PersonDocument', 'documents_details', 'template_id', 'person_document_id')
					->withPivot('value');
	}
}