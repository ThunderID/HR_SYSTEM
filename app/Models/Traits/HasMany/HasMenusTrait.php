<?php namespace App\Models\Traits\HasMany;

trait HasMenusTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasMenusTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function Menus()
	{
		return $this->hasMany('App\Models\Menu');
	}

	public function scopeChartID($query, $variable)
	{
		return $query->with(['menus', 'menus.authentications' => function($q)use($variable){$q->where('chart_id', $variable);}]);
	}
}