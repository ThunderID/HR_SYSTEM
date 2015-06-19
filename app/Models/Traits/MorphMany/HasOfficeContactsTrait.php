<?php namespace App\Models\Traits\MorphMany;

trait HasOfficeContactsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasOfficeContactsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/

	public function Contacts()
	{
		return $this->morphMany('App\Models\Contact', 'branch');
	}

	public function scopeContactID($query, $variable)
	{
		return $query->whereHas('contacts', function($q)use($variable){$q->id($variable);});
	}

	public function scopeDefaultContact($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->default($variable);}]);
	}
}