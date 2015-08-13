<?php namespace App\Models\Traits\MorphMany;

trait HasPersonWorkleavesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonWorkleavesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function PersonWorkleaves()
	{
		return $this->morphMany('App\Models\PersonWorkleave', 'queue_morph');
	}
}