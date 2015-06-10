<?php namespace App\Models\Traits\BelongsTo;

trait HasMenuTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasMenuTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function Menu()
	{
		return $this->belongsTo('App\Models\Menu');
	}

}