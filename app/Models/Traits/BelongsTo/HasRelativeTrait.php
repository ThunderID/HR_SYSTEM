<?php namespace App\Models\Traits\BelongsTo;

trait HasRelativeTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasRelativeTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/
	public function Relative()
	{
		return $this->belongsTo('App\Models\Person', 'relative_id');
	}

	public function scopeDefaultContact($query, $variable)
	{
		return $query->with(['relative.contacts' => function($q)use($variable){$q->default($variable);}]);
	}

	public function scopeRelativeOrganisationID($query, $variable)
	{
		return $query->whereHas('relative', function($q)use($variable){$q->organisationid($variable);})
					->with(['relative' => function($q)use($variable){$q->organisationid($variable);}]);
	}
}