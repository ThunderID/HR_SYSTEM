<?php namespace App\Models\Traits\HasMany;

trait HasWorkleavesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkleavesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WORKLEAVE PACKAGE -------------------------------------------------------------------*/

	public function Workleaves()
	{
		return $this->hasMany('App\Models\Workleave');
	}

}