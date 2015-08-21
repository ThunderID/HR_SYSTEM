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

	public function ScopeLastAttendanceLog($query, $variable)
	{
		if(!is_null($variable))
		{
			return $query->whereHas('attendancelogs', function($q)use($variable){$q;})
						->with(['attendancelogs' => function($q)use($variable){$q->notactualstatus('L')->orderBy('updated_at', 'desc');}, 'attendancelogs.modifiedby']);
		}

		return $query->whereHas('attendancelogs', function($q)use($variable){$q;})
					->with(['attendancelogs' => function($q)use($variable){$q->orderBy('updated_at', 'desc');}]);
	}

	public function ScopeUnsettleAttendanceLog($query, $variable)
	{
		return $query->whereHas('attendancelogs', function($q)use($variable){$q->wherenull('settlement_at');})
					->with(['attendancelogs' => function($q)use($variable){$q->wherenull('settlement_at')->orderBy('updated_at', 'desc');}]);
	}
}