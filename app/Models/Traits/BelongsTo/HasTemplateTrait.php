<?php namespace App\Models\Traits\BelongsTo;

trait HasTemplateTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasTemplateTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function Template()
	{
		return $this->belongsTo('App\Models\Template', 'template_id', 'id');
	}

}