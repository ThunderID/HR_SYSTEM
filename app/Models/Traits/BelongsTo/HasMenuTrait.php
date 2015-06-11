<?php namespace App\Models\Traits\BelongsTo;

trait HasMenuTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasMenuTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function Menu()
	{
		return $this->belongsTo('App\Models\Menu');
	}

	public function scopeMenuID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('menu_id', $variable);
		}

		return $query->where('menu_id', $variable);
	}
}