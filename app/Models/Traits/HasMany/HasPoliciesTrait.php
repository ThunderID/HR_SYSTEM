<?php namespace App\Models\Traits\HasMany;


trait HasPoliciesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPoliciesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Policies()
	{
		return $this->HasMany('App\Models\Policy')
	}
}