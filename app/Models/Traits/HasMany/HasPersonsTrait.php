<?php namespace App\Models\Traits\HasMany;

trait HasPersonsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Persons()
	{
		return $this->hasMany('App\Models\Person');
	}

}