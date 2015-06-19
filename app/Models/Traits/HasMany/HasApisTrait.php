<?php namespace App\Models\Traits\HasMany;

trait HasApisTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasApisTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Apis()
	{
		return $this->hasMany('App\Models\Api');
	}

	public function scopeApiID($query, $variable)
	{
		return $query->whereHas('apis', function($q)use($variable){$q->id($variable);});
	}
}
