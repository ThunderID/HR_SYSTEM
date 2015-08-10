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
}