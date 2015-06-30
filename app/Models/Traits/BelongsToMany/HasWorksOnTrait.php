<?php namespace App\Models\Traits\BelongsToMany;

use DateTime;

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
					->withPivot('status', 'start', 'end', 'reason_end_job');
	}

	public function WorksCalendars()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->branchid($variable);});
	}

	public function scopeChartID($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->id($variable);});
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
			return $query->whereDoesntHave('works', function($q)use($variable){$q;});
		}
		return $query->whereHas('works', function($q)use($variable){$q;});
	}

	public function scopeCurrentWork($query, $variable)
	{
		if(!is_null($variable))
		{
			return $query->with(['works' => function($q)use($variable){$q->whereNull('end')->orderBy('start', 'asc')->id($variable);}]);
		}

		return $query->with(['works' => function($q){$q->whereNull('end')->orderBy('start', 'asc');}]);
	}

	public function scopePreviousWork($query, $variable)
	{
		return $query->with(['works' => function($q){$q->whereNotNull('end')->orderBy('start', 'asc');}]);

	}

	public function scopeChartTag($query, $variable)
	{
		return $query->WhereHas('works', function($q)use($variable){$q->tag($variable);});
	}

	public function scopeChartChild($query, $variable)
	{
		return $query->WhereHas('works', function($q)use($variable){$q->child($variable);});
	}

	public function ScopeWorkCalendar($query, $variable)
	{
		if(isset($variable['id']))
		{
			return $query->whereHas('workscalendars' ,function($q)use($variable){$q->calendarid($variable['id']);});
		}
		return $query->whereHas('workscalendars' ,function($q)use($variable){$q;});
	}

	public function ScopeWorkCalendarSchedule($query, $variable)
	{
		return $query->whereHas('workscalendars.calendar.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}
}