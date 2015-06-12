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

	public function scopeDefaultContact($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->default($variable);}]);
	}

	public function scopeEmail($query, $variable)
	{
		return $query->whereHas('contacts', function($q)use($variable){$q->item('email')->value($variable)->default(true);});
	}

	public function scopeDefaultEmail($query, $variable)
	{
		return $query->with(['contacts' => function($q)use($variable){$q->item('email')->default($variable);}]);
	}
}