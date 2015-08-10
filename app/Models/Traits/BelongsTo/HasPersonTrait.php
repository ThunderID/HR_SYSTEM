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

	public function CreatedBy()
	{
		return $this->belongsTo('App\Models\Person', 'created_by', 'id');
	}

	public function ModifiedBy()
	{
		return $this->belongsTo('App\Models\Person', 'modified_by', 'id');
	}

	public function SettlementBy()
	{
		return $this->belongsTo('App\Models\Person', 'settlement_by', 'id');
	}

	public function scopePersonID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->wherein('person_id', $variable);
		}

		return $query->where('person_id', $variable);
	}

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

	public function ScopeCurrentWork($query, $variable)
	{
		return $query->WhereHas('person', function($q)use($variable){$q->CurrentWork($variable);});
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->WhereHas('person.works.branch', function($q)use($variable){$q->organisationid($variable);});
	}

	public function scopeBranchID($query, $variable)
	{
		return $query->WhereHas('person.works', function($q)use($variable){$q->branchid($variable);});
	}

	public function scopeChartTag($query, $variable)
	{
		return $query->WhereHas('person.works', function($q)use($variable){$q->tag($variable);});
	}
}