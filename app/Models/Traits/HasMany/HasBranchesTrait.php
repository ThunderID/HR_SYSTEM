<?php namespace App\Models\Traits\HasMany;

trait HasBranchesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasBranchesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Branches()
	{
		return $this->hasMany('App\Models\Branch');
	}

}