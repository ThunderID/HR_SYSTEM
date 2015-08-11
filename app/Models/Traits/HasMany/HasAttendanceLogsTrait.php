<?php namespace App\Models\Traits\HasMany;

trait HasAttendanceLogsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAttendanceLogsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE VIA WORK -------------------------------------------------------------------*/
	
	public function AttendanceLogs()
	{
		return $this->hasMany('App\Models\AttendanceLog');
	}

	public function ScopeAttendanceActualStatus($query, $variable)
	{
		return $query->whereHas('attendancelogs', function($q)use($variable){$q->actualstatus($variable);})
					->with(['attendancelogs' => function($q)use($variable){$q->actualstatus($variable);}]);
	}
}