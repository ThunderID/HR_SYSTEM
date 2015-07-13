<?php namespace App\Models\Traits\BelongsToMany;

trait HasAuthGroupsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAuthGroupsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function AuthGroups()
	{
		return $this->belongsToMany('App\Models\AuthGroup', 'tmp_groups_menus', 'tmp_menu_id', 'tmp_auth_group_id');
	}
}