<?php namespace App\Models\Traits\BelongsTo;

trait HasCalendarTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasCalendarTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/
	public function Calendar()
	{
		return $this->belongsTo('App\Models\Calendar');
	}

	public function scopeCalendarID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('calendar_id', $variable);
		}

		return $query->where('calendar_id', $variable);
	}
}