<?php namespace App\Models\Traits\BelongsTo;

trait HasProcessLogTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasProcessLogTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function ProcessLog()
	{
		return $this->belongsTo('App\Models\ProcessLog');
	}

	public function scopeProcessLogID($query, $variable)
	{
		if(is_array($variable))
		{
			if(isset($variable['fieldname']))
			{
				return $query->where($variable['fieldname'], $variable['variable']);
			}
		
			return $query->wherein('process_log_id', $variable);
		}

		return $query->where('process_log_id', $variable);
	}

	public function scopeProcessLogOndate($query, $variable)
	{
		return $query->wherehas('processlog', function($q)use($variable){$q->ondate($variable);})->groupby('process_log_id');
	}
	
	public function scopeOrganisationID($query, $variable)
	{
		return $query->WhereHas('processlog.person.works.branch', function($q)use($variable){$q->organisationid($variable);});
	}

	public function scopeProcessLogPersonID($query, $variable)
	{
		return $query->WhereHas('processlog', function($q)use($variable){$q->personid($variable);});
	}
}