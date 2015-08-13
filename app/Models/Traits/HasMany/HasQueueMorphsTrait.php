<?php namespace App\Models\Traits\HasMany;

trait HasQueueMorphsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasQueueMorphsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function QueueMorphs()
	{
		return $this->hasMany('App\Models\QueueMorph');
	}

}