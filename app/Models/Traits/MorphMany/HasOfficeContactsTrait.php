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

	public function scopeCurrentContact($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->where('is_default', true)->orderBy($variable, 'asc');}]);
	}

	public function TagContacts()
	{
		return $this->morphMany('App\Models\Contact', 'branch');
	}

	public function scopeGroupContacts($query, $variable)
	{
		return $query->with(['tagcontacts' => function($q)use($variable){$q->groupBy('item');}]);
	}
}