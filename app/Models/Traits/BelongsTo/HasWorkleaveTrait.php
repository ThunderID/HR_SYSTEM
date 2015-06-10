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
}