<?php namespace App\Models\Traits\HasMany;

use DB;

trait HasMaritalStatusesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasMaritalStatusesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function MaritalStatuses()
	{
		return $this->hasMany('App\Models\MaritalStatus');
	}
}