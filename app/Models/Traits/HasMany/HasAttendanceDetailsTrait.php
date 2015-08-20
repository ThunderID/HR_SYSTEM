<?php namespace App\Models\Traits\HasMany;

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
		return $this->hasMany('App\Models\AttendanceDetail');
	}
}