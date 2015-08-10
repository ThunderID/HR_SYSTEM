<?php namespace App\Models\Traits\BelongsTo;

trait HasAttendanceLogTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAttendanceLogTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function AttendanceLog()
	{
		return $this->belongsTo('App\Models\AttendanceLog');
	}
}