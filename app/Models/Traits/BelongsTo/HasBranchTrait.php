<?php namespace App\Models\Traits\BelongsTo;

trait HasBranchTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasBranchTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function Branch()
	{
		return $this->belongsTo('App\Models\Branch', 'branch_id');
	}


	public function scopeBranchID($query, $variable)
	{
		if(is_null($variable))
		{
			return $query->whereIn('branch_id', $variable);
		}

		return $query->where('branch_id', $variable);
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->WhereHas('branch', function($q)use($variable){$q->organisationid($variable);});
	}

	public function scopeOrBranchName($query, $variable)
	{
		$query =  $query->selectraw('hr_charts.*')->selectraw('hr_branches.name as branchname')->join('branches', 'branches.id', '=', 'charts.branch_id');
		if(is_array($variable))
		{
			foreach ($variable as $key => $value) 
			{
				$query 		= $query->orwhere('branches.name', 'like' ,'%'.$value.'%');
			}

			return $query;
		}
		return $query->orwhere('branches.name', 'like', '%'.$variable.'%');
	}

	public function scopeBranchName($query, $variable)
	{
		return $query->wherehas('branch', function($q)use($variable){$q->name($variable);});
	}
}