<?php namespace App\Models\Traits\HasMany;

trait HasWorkAuthenticationsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorkAuthenticationsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function WorkAuthentications()
	{
		return $this->hasMany('App\Models\WorkAuthentication');
	}
}