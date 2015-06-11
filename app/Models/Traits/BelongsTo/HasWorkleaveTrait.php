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

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Workleave()
	{
		return $this->belongsTo('App\Models\Workleave');
	}

	public function scopeWorkleaveID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->wherein('workleave_id', $variable);
		}

		return $query->where('workleave_id', $variable);
	}
}