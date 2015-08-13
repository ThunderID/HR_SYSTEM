<?php namespace App\Models\Traits\MorphTo;

trait HasQueueMorphTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasQueueMorphTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function QueueMorph()
	{
		return $this->morphTo();
	}

}