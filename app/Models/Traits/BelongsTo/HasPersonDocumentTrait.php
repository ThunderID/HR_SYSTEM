<?php namespace App\Models\Traits\BelongsTo;

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

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function PersonDocument()
	{
		return $this->belongsTo('App\Models\PersonDocument');
	}
}