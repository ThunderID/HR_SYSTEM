<?php namespace App\Models\Traits\MorphMany;

trait HasContactsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasContactsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CONTACT PACKAGE -------------------------------------------------------------------*/
	public function Contacts()
	{
		return $this->morphMany('App\Models\Contact', 'person');
	}

	public function TagContacts()
	{
		return $this->morphMany('App\Models\Contact', 'person');
	}

	public function scopeGroupContacts($query, $variable)
	{
		return $query->with(['tagcontacts' => function($q)use($variable){$q->groupBy('item');}]);
	}

	public function scopeCurrentContact($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->where('is_default', true)->orderBy($variable, 'asc');}]);
	}

	public function scopeEmail($query, $variable)
	{
		return $query->whereHas('contacts', function($q)use($variable){$q->where('item', 'email')->where('value', $variable)->where('is_default', true);});
	}

	public function scopeDefaultEmail($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->where('is_default', true)->where('item', 'email')->take(1);}]);
	}
}