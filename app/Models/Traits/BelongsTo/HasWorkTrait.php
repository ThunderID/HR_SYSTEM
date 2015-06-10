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

}