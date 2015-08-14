<?php namespace App\Models\Traits\HasMany;

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

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/
	public function Schedules()
	{
		return $this->hasMany('App\Models\Schedule');
	}

	public function ScopeSchedulesOndate($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable);})->with(['schedules' => function($q)use($variable){$q->ondate($variable);}]);
	}
}