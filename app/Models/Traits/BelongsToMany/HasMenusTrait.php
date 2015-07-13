<?php namespace App\Models\Traits\BelongsToMany;

trait HasMenusTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasMenusTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/
	
	public function Menus()
	{
		return $this->belongsToMany('App\Models\Menu', 'tmp_groups_menus', 'tmp_auth_group_id', 'tmp_menu_id')
					->withPivot('id');
	}

}
