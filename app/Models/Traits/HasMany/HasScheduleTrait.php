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
					->currentwork(true)
					->defaultemail(true)
					// ->selectRaw('if(person_schedules.on = "'.$variable.'", person_schedules.on, if(tmp_schedules.on = "'.$variable.'", tmp_schedules.on, "'.$variable.'")) as ondate')
					// ->selectRaw('if(person_schedules.on = "'.$variable.'", person_schedules.start, if(tmp_schedules.on = "'.$variable.'", tmp_schedules.start, "'.$variable.'")) as onstart')
					// ->selectRaw('if(person_schedules.on = "'.$variable.'", person_schedules.end, if(tmp_schedules.on = "'.$variable.'", tmp_schedules.end, "'.$variable.'")) as onend')
					// ->selectRaw('persons.id')
					// ->selectRaw('persons.name')
					// ->selectRaw('charts.name as chart')
					// ->selectRaw('branches.name as branch')
					// ->selectRaw('charts.tag as tag')
					// ->Join('works', 'persons.id', '=', 'works.person_id')
					// ->Join('charts', 'works.chart_id', '=', 'charts.id')
					// ->Join('branches', 'charts.branch_id', '=', 'branches.id')
					// ->Join('tmp_calendars', 'tmp_calendars.id', '=', 'works.calendar_id')
					// ->leftJoin('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
					// ->leftJoin('tmp_schedules', 'tmp_calendars.id', '=', 'tmp_schedules.calendar_id')
					// ->whereRaw('NOT EXISTS (SELECT * FROM logs WHERE logs.on = "'.$variable.'" && logs.person_id = persons.id)')
                    // ->whereNull('works.end')
                    // ->where('person_schedules.is_affect_workleave', false)
                    // ->groupBy('persons.id')
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
					->where('status', 'absence_workleave')
					->groupBy('person_schedules.name')
					->groupBy('persons.id');
	}
}