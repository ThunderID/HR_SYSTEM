<?php namespace App\Models\Traits\HasMany;

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
		return $this->hasMany('App\Models\Document');
	}

}