<?php namespace App\Models\Traits\MorphMany;

trait HasSchedulesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasSchedulesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function Schedules()
	{
		return $this->morphedByMany('App\Models\Schedule', 'queue_morph');
	}
}