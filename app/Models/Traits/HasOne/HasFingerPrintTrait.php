<?php namespace App\Models\Traits\HasOne;

trait HasFingerPrintTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFingerPrintTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function FingerPrint()
	{
		return $this->hasOne('App\Models\FingerPrint');
	}
}
