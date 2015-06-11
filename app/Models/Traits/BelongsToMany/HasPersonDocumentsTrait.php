<?php namespace App\Models\Traits\BelongsToMany;

use DateTime;

trait HasPersonDocumentsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonDocumentsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Persons()
	{
		return $this->belongsToMany('App\Models\Person', 'persons_documents', 'document_id', 'person_id');
	}
}