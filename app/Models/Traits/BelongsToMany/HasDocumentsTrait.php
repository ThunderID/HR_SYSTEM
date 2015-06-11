<?php namespace App\Models\Traits\BelongsToMany;

trait HasDocumentsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDocumentsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCLATE PACKAGE -------------------------------------------------------------------*/
	public function Documents()
	{
		return $this->belongsToMany('App\Models\Document', 'persons_documents', 'person_id', 'document_id')
					->withPivot('created_at');
	}
}