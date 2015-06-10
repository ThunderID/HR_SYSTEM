<?php namespace App\Models\Traits\BelongsTo;

trait HasChartTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasChartTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Chart()
	{
		return $this->belongsTo('App\Models\Chart', 'chart_id');
	}

	public function scopeEmail($query, $variable)
	{
		return $query->whereHas('chart', function($q){$q;})
		->with(['chart.works' => function($q){$q->whereNull('works.end')->defaultemail(true);}]);
	}
}