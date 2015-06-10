<?php namespace App\Models\Traits\BelongsToMany;

trait HasWorksTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorksTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE VIA WORK -------------------------------------------------------------------*/
	
	public function Works()
	{
		return $this->belongsToMany('App\Models\Person', 'works', 'chart_id', 'person_id')
				->withPivot('status','start', 'end', 'id');
	}

	public function CheckWorks()
	{
		return $this->belongsToMany('App\Models\Chart', 'works', 'chart_id', 'person_id')
					->withPivot('status', 'start', 'end', 'reason_end_job')->whereNull('end');
	}
}