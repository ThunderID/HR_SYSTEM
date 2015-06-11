<?php namespace App\Models\Traits\HasMany;

trait HasWidgetsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWidgetsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WIDBOARD PACKAGE -------------------------------------------------------------------*/

	public function Widgets()
	{
		return $this->hasMany('App\Models\PersonWidget');
	}
}