<?php namespace App\Models\Traits\BelongsToMany;


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

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Persons()
	{
		return $this->belongsToMany('App\Models\Person', 'persons_workleaves', 'workleave_id', 'person_id')
					->withPivot('start', 'end');
	}
}