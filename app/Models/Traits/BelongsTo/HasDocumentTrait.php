<?php namespace App\Models\Traits\BelongsTo;

trait HasDocumentTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasDocumentTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN DOCUMENT PACKAGE -------------------------------------------------------------------*/

	public function Document()
	{
		return $this->belongsTo('App\Models\Document');
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->wherehas('document.organisation', function($q)use($variable){$q->where('id', $variable);});
	}

	public function scopeTag($query, $variable)
	{
		return $query->wherehas('document', function($q)use($variable){$q->where('tag', $variable);});
	}
}