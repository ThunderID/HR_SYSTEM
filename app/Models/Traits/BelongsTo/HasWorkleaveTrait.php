<?php namespace App\Models\Traits\BelongsTo;


trait HasWorkleaveTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkleaveTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Workleave()
	{
		return $this->belongsTo('App\Models\Workleave');
	}

	public function scopeWorkleaveID($query, $variable)
	{
		if(is_array($variable))
		{
			if(isset($variable['fieldname']))
			{
				return $query->where($variable['fieldname'], $variable['variable']);
			}
		
			return $query->wherein('workleave_id', $variable);
		}

		return $query->where('workleave_id', $variable);
	}

	public function scopeWorkleaveStatus($query, $variable)
	{
		return $query->whereHas('workleave', function($q)use($variable){$q->status($variable);});
	}
}