<?php namespace App\Models\Traits\BelongsToMany;

trait HasFollowCalendarsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFollowCalendarsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CALENDAR PACKAGE -------------------------------------------------------------------*/

	public function Calendars()
	{
		return $this->belongsToMany('App\Models\Calendar', 'follows', 'chart_id', 'calendar_id');
	}
}