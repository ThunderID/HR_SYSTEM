<?php namespace App\Models\Traits\MorphTo;

trait HasPersonWorkleaveTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonWorkleaveTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function PersonWorkleave()
	{
		return $this->morphTo();
	}

}