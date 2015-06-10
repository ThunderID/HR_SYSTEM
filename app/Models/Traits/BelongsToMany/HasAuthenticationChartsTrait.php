<?php namespace App\Models\Traits\BelongsToMany;

trait HasAuthenticationChartsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAuthenticationChartsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Charts()
	{
		return $this->belongsToMany('App\Models\Chart', 'authentications', 'menu_id', 'chart_id');
	}
}