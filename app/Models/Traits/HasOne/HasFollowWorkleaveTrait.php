<?php namespace App\Models\Traits\HasOne;

trait HasFollowWorkleaveTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFollowWorkleaveTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN FINGER PACKAGE -------------------------------------------------------------------*/

	public function FollowWorkleave()
	{
		return $this->hasOne('App\Models\FollowWorkleave');
	}

	public function scopeWorkleaveID($query, $variable)
	{
		return $query->WhereHas('followworkleave', function($q)use($variable){$q->workleaveid($variable);});
	}

	public function scopeCurrentWorkleave($query, $variable)
	{
		return $query->WhereHas('followworkleave', function($q)use($variable){$q;})->with(['followworkleave', 'followworkleave.workleave']);
	}
}