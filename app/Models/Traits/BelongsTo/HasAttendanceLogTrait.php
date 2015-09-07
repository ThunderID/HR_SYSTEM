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

	public function scopeProcessLogOndate($query, $variable)
	{
		return $query->wherehas( 'attendancelog.processlog', function($q)use($variable){$q->ondate($variable);})->groupby('attendance_log_id');
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->WhereHas('attendancelog', function($q)use($variable){$q->organisationid($variable);});
	}
}