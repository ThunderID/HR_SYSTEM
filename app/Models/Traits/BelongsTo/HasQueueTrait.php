<?php namespace App\Models\Traits\BelongsTo;

trait HasQueueTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasQueueTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/
	public function Queue()
	{
		return $this->belongsTo('App\Models\Queue');
	}
}