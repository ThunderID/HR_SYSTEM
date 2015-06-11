<?php namespace App\Models\Traits\BelongsTo;

trait HasOrganisationTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasOrganisationTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/
	public function Organisation()
	{
		return $this->belongsTo('App\Models\Organisation');
	}

	public function scopeOrganisationID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->wherein('organisation_id', $variable);
		}

		return $query->where('organisation_id', $variable);
	}
}