<?php namespace App\Models\Traits\BelongsToMany;

trait HasCalendarsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasCalendarsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function Calendars()
	{
		return $this->belongsToMany('App\Models\Calendar', 'persons_calendars', 'person_id', 'calendar_id')
					->withPivot('start');
	}

	public function ScopeCalendar($query, $variable)
	{
		return $query->whereHas('calendars' ,function($q)use($variable){$q->start($variable['start']);});
	}

	public function ScopeCalendarSchedule($query, $variable)
	{
		return $query->whereHas('calendars.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}
}