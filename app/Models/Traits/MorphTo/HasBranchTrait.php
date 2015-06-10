<?php namespace App\Models\Traits\MorphTo;

trait HasBranchTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasBranchTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Branch()
	{
		return $this->morphTo();
	}

}