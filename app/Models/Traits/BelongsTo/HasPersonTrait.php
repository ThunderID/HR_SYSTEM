<?php namespace App\Models\Traits\BelongsTo;

trait HasPersonTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/
	public function Person()
	{
		return $this->belongsTo('App\Models\Person');
	}

	// public function scopeChartTag($query, $variable)
	// {
	// 	return $query->WhereHas('person.works', function($q)use($variable){$q->where('tag', $variable);});
	// }

	// public function scopeBranchName($query, $variable)
	// {
	// 	return $query->WhereHas('person.works.branch', function($q)use($variable){$q->where('name', $variable);});
	// }

	// public function scopeOrganisationID($query, $variable)
	// {
	// 	return $query->WhereHas('person.works.branch.organisation', function($q)use($variable){$q->where('id', $variable);});
	// }

	public function ScopeHasNoSchedule($query, $variable)
	{
		return $query->whereDoesntHave('person.schedules' ,function($q)use($variable){$q->ondate($variable['on']);});
	}

	public function ScopeCalendar($query, $variable)
	{
		return $query->whereHas('person.calendars' ,function($q)use($variable){$q->start($variable['start'])->id($variable['id']);});
	}

	public function ScopeWorkCalendar($query, $variable)
	{
		return $query->whereHas('person' ,function($q)use($variable){$q->CheckWork(true)->WorkCalendar(['start' => $variable['start'], 'id' => $variable['id']]);});
	}

	public function ScopeStillWork($query, $variable)
	{
		return $query->WhereHas('person', function($q)use($variable){$q->CurrentWork($variable);});
	}

	public function scopeCurrentWork($query, $variable)
	{
		return $query->whereHas('person.works', function($q){$q;})
					->with(['person' => function($q){$q->whereHas('works', function($q){$q;});}]);
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->WhereHas('person.works.branch', function($q)use($variable){$q->where('id', $variable);});
	}
}