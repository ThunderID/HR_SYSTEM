<?php namespace App\Models\Traits\HasMany;

use DateTime;

trait HasChartsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasChartsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Charts()
	{
		return $this->hasMany('App\Models\Chart');
	}
}
