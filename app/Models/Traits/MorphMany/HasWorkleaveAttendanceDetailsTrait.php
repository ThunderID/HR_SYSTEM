<?php namespace App\Models\Traits\MorphMany;

trait HasWorkleaveAttendanceDetailsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkleaveAttendanceDetailsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function WorkleaveAttendanceDetails()
	{
		return $this->morphMany('App\Models\WorkleaveAttendanceDetail', 'workleave');
	}

	public function scopeAttendanceDetailID($query, $variable)
	{
		return $query->whereHas('workleaveattendancedetails', function($q)use($variable){$q->id($variable);});
	}
}