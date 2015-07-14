<?php namespace App\Models\Traits\HasMany;

use DB;

trait HasScheduleTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasScheduleTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function Schedules()
	{
		return $this->hasMany('App\Models\PersonSchedule');
	}

	public function ScopeSchedule($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}

	public function ScopeMaxEndSchedule($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable['on']);})
					->with(['schedules' => function($q)use($variable){$q->ondate($variable['on'])->orderBy('end', 'desc');}]);
	}

	public function ScopeMinStartSchedule($query, $variable)
	{
		return $query->whereHas('schedules' ,function($q)use($variable){$q->ondate($variable['on']);})
					->with(['schedules' => function($q)use($variable){$q->ondate($variable['on'])->orderBy('start', 'asc');}]);
	}

	public function ScopeTakenWorkleave($query, $variable)
	{
		return $query->with(['schedules' => function($q)use($variable){$q->status($variable['status'])->ondate($variable['on'])->affectsalary(true);}]);
	}

	public function ScopeCheckTakenWorkleave($query, $variable)
	{
		return $query->whereHas('schedules', function($q)use($variable){$q->status($variable['status'])->ondate($variable['on'])->affectsalary(true);});
	}

	public function ScopeFullSchedule($query, $variable)
	{
		return $query
					->wheredoesnthave('processlogs', function($q)use($variable){$q->ondate([$variable, $variable]);})
					->chartnotadmin(true)
					;
	}

	public function ScopeMinusQuotas($query, $variable)
	{
		return $query->selectRaw('count(start) as minus_quota')
					->selectRaw('person_schedules.name as name')
					->selectRaw('person_id')
					->join('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
					->whereIn('persons.id', $variable['ids'])
					->where('person_schedules.on', '>=', date('Y-m-d',strtotime($variable['ondate'][0])))
					->where('person_schedules.on', '<=', date('Y-m-d',strtotime($variable['ondate'][1])))
					->whereIn('status', ['UL', 'CN', 'CI', 'CB'])
					->groupBy('person_schedules.name')
					->groupBy('persons.id');
	}
}