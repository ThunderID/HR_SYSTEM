<?php namespace App\Models\Traits\BelongsToMany;

use DateTime;

trait HasPersonDocumentsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasPersonDocumentsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Persons()
	{
		return $this->belongsToMany('App\Models\Person', 'persons_documents', 'document_id', 'person_id');
	}

	public function scopeCheckPerson($query, $variable)
	{
		return $query->select('documents.*')
					 ->join('persons_documents', 'documents.id', '=', 'persons_documents.document_id')
					 ->where('person_id', $variable);
	}

	public function scopeCountPerson($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->with(['persons' => function($q)use($days){$q->where('persons_documents.created_at', '>=', $days->format('Y-m-d'))->selectRaw('count(*) as count')->groupBy('document_id');}]);
		}

		return $query->with(['persons' => function($q){$q->selectRaw('count(*) as count')->groupBy('document_id');}]);
	}

	public function scopeCheckReceiver($query, $variable)
	{
		if(strtotime($variable))
		{
			$days = new DateTime($variable);
			return $query->with(['persons' => function($q)use($days){$q->where('persons_documents.created_at', '>=', $days->format('Y-m-d'));}]);
		}

		return $query->whereHas('persons', function($q)use($variable){$q;});
	}
}