<?php namespace App\Models\Traits\BelongsToMany;

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
		return $this->belongsToMany('App\Models\Chart', 'follows', 'calendar_id', 'chart_id');
	}

	public function scopeChartTag($query, $variable)
	{
		return $query->WhereHas('charts', function($q)use($variable){$q->tag($variable);});
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->WhereHas('charts', function($q)use($variable){$q->branchid($variable);});
	}
}