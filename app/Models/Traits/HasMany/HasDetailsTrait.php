<?php namespace App\Models\Traits\HasMany;

trait HasDetailsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDetailsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function Details()
	{
		return $this->hasMany('App\Models\DocumentDetail');
	}
}