<?php namespace App\Models\Traits\HasMany;

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

	/* ------------------------------------------------------------------- RELATIONSHIP IN Schedule PACKAGE -------------------------------------------------------------------*/

	public function Calendars()
	{
		return $this->hasMany('App\Models\Calendar');
	}

	public function Childs()
	{
		return $this->hasMany('App\Models\Calendar', 'import_from_id');
	}
}