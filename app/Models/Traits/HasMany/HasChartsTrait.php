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

	public function scopeChartChild($query, $variable)
	{
		return $query->with(['charts' => function($q)use($variable){$q->child($variable);}]);
	}
}
