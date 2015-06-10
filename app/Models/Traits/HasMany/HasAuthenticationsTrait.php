<?php namespace App\Models\Traits\HasMany;

trait HasAuthenticationsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAuthenticationsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function Authentications()
	{
		return $this->hasMany('App\Models\Authentication');
	}

}