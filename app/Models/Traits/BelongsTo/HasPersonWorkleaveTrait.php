<?php namespace App\Models\Traits\BelongsTo;


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

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Parent()
	{
		return $this->belongsTo('App\Models\PersonWorkleave', 'person_workleave_id');
	}
}