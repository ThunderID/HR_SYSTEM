<?php namespace App\Models\Traits\MorphTo;

trait HasPersonTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/
	public function Person()
	{
		return $this->morphTo();
	}

}