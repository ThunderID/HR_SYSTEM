<?php namespace App\Models\Traits\BelongsToMany;


trait HasWorkleavesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkleavesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Workleaves()
	{
		return $this->belongsToMany('App\Models\Workleave', 'persons_workleaves', 'person_id', 'workleave_id')
					->withPivot('start', 'end');
	}
}