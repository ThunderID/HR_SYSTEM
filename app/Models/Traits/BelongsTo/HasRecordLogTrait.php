<?php namespace App\Models\Traits\BelongsTo;

trait HasRecordLogTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasRecordLogTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function RecordLog()
	{
		return $this->belongsTo('App\Models\RecordLog');
	}

	public function Parent()
	{
		return $this->belongsTo('App\Models\RecordLog', 'parent_id');
	}

	public function scopeRecordLogID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('parent_id', $variable);
		}

		return $query->where('parent_id', $variable);
	}
}