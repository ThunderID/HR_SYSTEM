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
					->withPivot('status', 'start', 'end');
	}

	public function Experiences()
	{
		return $this->belongsToMany('App\Models\Chart', 'works', 'person_id', 'chart_id')
					->withPivot('status', 'start', 'end', 'reason_end_job');
	}

	public function WorksCalendars()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function scopeCheckWork($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->whereHas('works', function($q)use($days){$q->where('start', '>=', $days->format('Y-m-d'));});
		}
		if($variable==false)
		{
			return $query->whereDoesntHave('works', function($q)use($variable){$q;});
		}
		return $query->whereHas('works', function($q)use($variable){$q;});
	}

	public function scopeCheckResign($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			
			return $query->whereHas('experiences', function($q)use($days){$q->where('end', '<', $days->format('Y-m-d'));});
		}
		return $query->whereHas('experiences', function($q)use($variable){$q->where('end', '<', $variable);});
	}

	public function scopeCurrentWork($query, $variable)
	{
		if(!is_null($variable))
		{
			return $query->whereHas('works', function($q){$q->whereNull('end')->orderBy('start', 'asc');})
					->whereHas('works.branch', function($q)use($variable){$q->organisationid($variable);})
					->with(['works' => function($q){$q->whereNull('end')->orderBy('start', 'asc');}, 'works.branch' => function($q)use($variable){$q->organisationid($variable);}]);
		}
		return $query->with(['works' => function($q){$q->whereNull('end')->orderBy('start', 'asc');}, 'works.branch.organisation' => function($q)use($variable){$q;}]);
	}

	public function scopeCheckApps($query, $variable)
	{
		return $query->with(['works.applications']);
	}

	// public function scopeOrganisationID($query, $variable)
	// {
	// 	return $query->whereHas('works.branch.organisation', function($q)use($variable){$q->where('id', $variable);});
	// }

	public function scopeBranchName($query, $variable)
	{
		return $query->whereHas('works.branch', function($q)use($variable){$q->where('name', 'like', '%'.$variable.'%');});
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->whereHas('works.branch', function($q)use($variable){$q->where('id', $variable);});
	}

	public function scopeChartID($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->where('charts.id', $variable);});
	}

	public function scopeChartTag($query, $variable)
	{
		return $query->WhereHas('works', function($q)use($variable){$q->where('tag', 'like', '%'.$variable.'%');});
	}

	public function scopeExperiences($query, $variable)
	{
		return $query->with(['experiences' => function($q)use($variable){$q->orderBy($variable, 'asc')->take(10);}, 'experiences.branch.organisation']);
	}

	public function ScopeWorkCalendar($query, $variable)
	{
		if(isset($variable['id']))
		{
			return $query->whereHas('workscalendars' ,function($q)use($variable){$q->where('calendar_id', $variable['id']);});
		}
		return $query->whereHas('workscalendars' ,function($q)use($variable){$q;});
	}

	public function ScopeWorkCalendarSchedule($query, $variable)
	{
		return $query->whereHas('workscalendars.calendar.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}
}