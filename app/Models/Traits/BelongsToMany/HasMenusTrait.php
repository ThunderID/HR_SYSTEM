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
		return $this->belongsToMany('App\Models\Menu', 'authentications', 'chart_id', 'menu_id')
					->withPivot('is_create', 'is_read', 'is_update', 'is_delete', 'id');
	}

}
