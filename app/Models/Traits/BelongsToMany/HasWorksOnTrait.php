<?php namespace App\Models\Traits\BelongsToMany;

use DateTime, DB;

trait HasWorksOnTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorksOnTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION VIA WORK PACKAGE -------------------------------------------------------------------*/
	public function Works()
	{
		return $this->belongsToMany('App\Models\Chart', 'works', 'person_id', 'chart_id')
					->withPivot('status', 'start', 'end', 'reason_end_job', 'id');
	}

	public function WorksCalendars()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->branchid($variable)->wherenull('end');});
	}

	public function scopeChartID($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->id($variable)->wherenull('end');});
	}

	public function scopeCheckWork($query, $variable)
	{
		$bool 						= filter_var($variable, FILTER_VALIDATE_BOOLEAN);
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('works', function($q)use($days){$q->where('start', '>=', $days->format('Y-m-d'));});
		}
		if($bool==false)
		{
			return $query->whereDoesntHave('works', function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')));});
		}
		if($bool==true)
		{
			return $query->whereHas('works', function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')));});
		}
		
		return $query->whereHas('works', function($q)use($variable){$q;});
	}

	public function scopeCurrentWork($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->with(['works' => function($q)use($days){$q->whereNull('end')->orwhere('end', '<=', $days->format('Y-m-d'))->orderBy('start', 'asc');}]);
		}
		if(!is_null($variable))
		{
			return $query->with(['works' => function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')))->orderBy('start', 'asc')->id($variable);}]);
		}
		return $query->with(['works' => function($q){$q->where(DB::raw('`end` >= '.date('Y-m-d')))->orderBy('start', 'asc');}]);
	}

	public function scopePreviousWork($query, $variable)
	{
		return $query->with(['works' => function($q){$q->whereNotNull('end')->orwhere('end', '<', date('Y-m-d'))->orderBy('start', 'asc');}]);

	}

	public function scopeChartTag($query, $variable)
	{
		return $query->WhereHas('works', function($q)use($variable){$q->tag($variable)->where(DB::raw('`end` >= '.date('Y-m-d')));});
	}

	public function scopeChartChild($query, $variable)
	{
		return $query->WhereHas('works', function($q)use($variable){$q->child($variable);});
	}

	public function scopeChartNotAdmin($query, $variable)
	{
		return $query->WhereHas('workscalendars', function($q)use($variable){$q->status(['permanent', 'contract', 'probation', 'internship', 'permanent','others'])->where(DB::raw('`end` >= '.$variable));});
	}

	public function ScopeWorkCalendar($query, $variable)
	{
		if(isset($variable['id']))
		{
			return $query->whereHas('workscalendars' ,function($q)use($variable){$q->calendarid($variable['id']);});
		}
		$bool 						= filter_var($variable, FILTER_VALIDATE_BOOLEAN);
		$date 						= date('Y-m-d', strtotime($variable));
		if($bool==true)
		{
			return $query->whereHas('workscalendars', function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')));});
		}
		elseif($date)
		{
			return $query->whereHas('workscalendars', function($q)use($date){$q->where(DB::raw('`end` >= '.$date));});
		}
		return $query->whereHas('workscalendars' ,function($q)use($variable){$q;});
	}

	public function ScopeWithWorkSchedules($query, $variable)
	{
		if(isset($variable['id']))
		{
			return $query->with(['workscalendars' => function($q)use($variable){$q->calendarid($variable['id']);}, 'workscalendars.calendar']);
		}
		$bool 						= filter_var($variable, FILTER_VALIDATE_BOOLEAN);
		if($bool==true)
		{
			return $query->with(['workscalendars' => function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')));}, 'workscalendars.calendar']);
		}
		return $query->with(['workscalendars' => function($q)use($variable){$q;}, 'workscalendars.calendar']);
	}

	public function ScopeWithWorkCalendarSchedules($query, $variable)
	{
		return $query->with(['workscalendars' => function($q)use($variable){$q->where(DB::raw('`end` >= '.date('Y-m-d')));}, 'workscalendars.calendar', 'workscalendars.calendar.schedules' => function($q)use($variable){$q->ondate($variable['on']);}]);
	}

	public function ScopeWorkCalendarSchedule($query, $variable)
	{
		return $query->whereHas('workscalendars.calendar.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}
}