<?php namespace App\Models\Traits\MorphMany;

trait HasAttendanceDetailsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAttendanceDetailsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function AttendanceDetails()
	{
		return $this->morphMany('App\Models\AttendanceDetail', 'document');
	}

	public function scopeAttendanceDetailID($query, $variable)
	{
		return $query->whereHas('attendancedetails', function($q)use($variable){$q->id($variable);});
	}
}