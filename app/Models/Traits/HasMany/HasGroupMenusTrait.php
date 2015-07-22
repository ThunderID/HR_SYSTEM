<?php namespace App\Models\Traits\HasMany;

trait HasGroupMenusTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasGroupMenusTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function GroupMenus()
	{
		return $this->hasMany('App\Models\GroupMenu', 'tmp_auth_group_id');
	}
}