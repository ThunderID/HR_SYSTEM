<?php namespace App\Models\Traits\HasMany;

trait HasTemplatesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasTemplatesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/
	
	public function Templates()
	{
		return $this->hasMany('App\Models\Template');
	}
}