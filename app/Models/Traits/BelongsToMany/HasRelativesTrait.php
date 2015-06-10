<?php namespace App\Models\Traits\BelongsToMany;

trait HasRelativesTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasRelativesTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE -------------------------------------------------------------------*/

	public function Relatives()
	{
		return $this->belongsToMany('App\Models\Person', 'relatives', 'person_id', 'relative_id')
				->withPivot('relationship', 'id');
	}

	public function Person()
	{
		return $this->belongsToMany('App\Models\Person', 'relatives', 'relative_id', 'person_id')
				->withPivot('relationship');
	}

	public function scopeCheckRelation($query, $variable)
	{
		return $query->select('persons.*', 'persons.id as relative_id', 'relatives.relationship as relationship')
					 ->join('relatives', 'persons.id', '=', 'relatives.relative_id')
					 ->where('person_id', $variable)
					 ->whereNull('relatives.deleted_at');
	}

	public function scopeCheckRelationOf($query, $variable)
	{
		return $query->select('persons.*', 'persons.id as relative_id', 'relatives.relationship as relationship')
					 ->join('relatives', 'persons.id', '=', 'relatives.person_id')
					 ->where('relative_id', $variable)
					 ->whereNull('relatives.deleted_at');
	}

	public function scopeRelativeOrganisationID($query, $variable)
	{
		return $query->select('persons.*')
					 ->join('relatives', 'persons.id', '=', 'relatives.relative_id')
					 ->where('organisation_id', $variable);
	}

	public function scopeCheckRelative($query, $variable)
	{
		return $query->with(['relatives' => function($q){$q->take(1);}]);
	}
}
