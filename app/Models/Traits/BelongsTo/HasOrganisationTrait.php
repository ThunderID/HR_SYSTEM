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


	// public function scopeChartTag($query, $variable)
	// {
	// 	return $query->WhereHas('organisation.branches.charts', function($q)use($variable){$q->where('tag', $variable);});
	// }

	// public function scopeBranchName($query, $variable)
	// {
	// 	return $query->WhereHas('organisation.branches', function($q)use($variable){$q->where('name', $variable);});
	// }

	// public function scopeBranchID($query, $variable)
	// {
	// 	return $query->whereHas('persons.works.branch', function($q)use($variable){$q->id($variable);});
	// }

}