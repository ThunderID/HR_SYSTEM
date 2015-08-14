<?php namespace App\Models\Traits\MorphMany;

trait HasPersonSchedulesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonSchedulesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function PersonSchedules()
	{
		return $this->morphMany('App\Models\PersonSchedule', 'queue_morph');
	}
}