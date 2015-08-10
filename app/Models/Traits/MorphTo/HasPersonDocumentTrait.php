<?php namespace App\Models\Traits\MorphTo;

trait HasPersonDocumentTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonDocumentTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function PersonDocument()
	{
		return $this->morphTo();
	}

}