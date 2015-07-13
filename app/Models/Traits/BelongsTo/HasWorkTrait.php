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
}