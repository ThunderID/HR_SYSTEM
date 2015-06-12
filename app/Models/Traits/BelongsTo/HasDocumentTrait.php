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

	public function scopeDocumentID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('document_id', $variable);
		}

		return $query->where('document_id', $variable);
	}

	public function scopeDocumentTag($query, $variable)
	{
		return $query->wherehas('document', function($q)use($variable){$q->tag($variable);});
	}
}