<?php namespace App\Models\Traits\BelongsTo;

trait HasWorkTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WORK PACKAGE -------------------------------------------------------------------*/
	public function Work()
	{
		return $this->belongsTo('App\Models\Work');
	}

	public function scopeWorkID($query, $variable)
	{
		if(is_array($variable))
		{
			if(isset($variable['fieldname']))
			{
				return $query->where($variable['fieldname'], $variable['variable']);
			}
			return $query->wherein('work_id', $variable);
		}

		return $query->where('work_id', $variable);
	}

	public function scopeWorkOrganisationID($query, $variable)
	{
		return $query->WhereHas('work.chart.branch', function($q)use($variable){$q->organisationid($variable);});
	}
}