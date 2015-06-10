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

	public function TakenWorkleaves()
	{
		return $this->hasMany('App\Models\PersonSchedule');
	}

	public function ScopeTakenWorkleave($query, $variable)
	{
		return $query->with(['takenworkleaves' => function($q)use($variable){$q->status($variable['status'])->ondate($variable['on'])->affectsalary(true);}]);
	}

	public function ScopeCheckTakenWorkleave($query, $variable)
	{
		return $query->whereHas('takenworkleaves', function($q)use($variable){$q->status($variable['status'])->ondate($variable['on'])->affectsalary(true);});
	}

	public function ScopeFullSchedule($query, $variable)
	{
		// if(is_array($variable))
		// {
		// 	return $query
		// 			->selectRaw('if(hr_person_schedules.on >= "'.$variable[0].'" && hr_person_schedules.on <= "'.$variable[1].'", hr_person_schedules.on, if(hr_schedules.on >= "'.$variable[0].'" && hr_schedules.on <= "'.$variable[1].'", hr_schedules.on, "Not Scheduled")) as ondate')
		// 			->selectRaw('hr_persons.name')
		// 			->Join('works', 'persons.id', '=', 'works.person_id')
		// 			->Join('calendars', 'calendars.id', '=', 'works.calendar_id')
		// 			->leftJoin('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
		// 			->leftJoin('schedules', 'calendars.id', '=', 'schedules.calendar_id')
		// 			->whereRaw('NOT EXISTS (SELECT * FROM hr_logs WHERE hr_logs.on >= "'.$variable[0].'" && hr_logs.on <= "'.$variable[1].'" && hr_logs.person_id = hr_persons.id)')
		// 			->whereNull('works.end')
		// 			->orderBy(DB::Raw('if(hr_person_schedules.on >= "'.$variable[0].'" && hr_person_schedules.on <= "'.$variable[1].'", hr_person_schedules.on, if(hr_schedules.on >= "'.$variable[0].'" && hr_schedules.on <= "'.$variable[1].'", hr_schedules.on, "Not Scheduled"))'), 'desc')
		// 			->groupBy('persons.id')
		// 			;			
		// }
		return $query
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.on, if(hr_schedules.on = "'.$variable.'", hr_schedules.on, "'.$variable.'")) as ondate')
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.start, if(hr_schedules.on = "'.$variable.'", hr_schedules.start, "'.$variable.'")) as onstart')
					->selectRaw('if(hr_person_schedules.on = "'.$variable.'", hr_person_schedules.end, if(hr_schedules.on = "'.$variable.'", hr_schedules.end, "'.$variable.'")) as onend')
					->selectRaw('hr_persons.id')
					->selectRaw('hr_persons.name')
					->selectRaw('hr_charts.name as chart')
					->selectRaw('hr_branches.name as branch')
					->selectRaw('hr_charts.tag as tag')
					->Join('works', 'persons.id', '=', 'works.person_id')
					->Join('charts', 'works.chart_id', '=', 'charts.id')
					->Join('branches', 'charts.branch_id', '=', 'branches.id')
					->Join('calendars', 'calendars.id', '=', 'works.calendar_id')
					->leftJoin('person_schedules', 'persons.id', '=', 'person_schedules.person_id')
					->leftJoin('schedules', 'calendars.id', '=', 'schedules.calendar_id')
					->whereRaw('NOT EXISTS (SELECT * FROM hr_logs WHERE hr_logs.on = "'.$variable.'" && hr_logs.person_id = hr_persons.id)')
                    ->whereNull('works.end')
                    // ->where('person_schedules.is_affect_workleave', false)
                    ->groupBy('persons.id')
					;
	}

	public function ScopeMinusQuotas($query, $variable)
	{
		return $query->selectRaw('count(start) as minus_quota')
					->selectRaw('hr_person_schedules.name as name')
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