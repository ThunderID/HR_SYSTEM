<?php namespace App\Models\Traits\BelongsToMany;

trait HasFollowedWorksTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasFollowedWorksTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE VIA WORK -------------------------------------------------------------------*/
	
	public function FollowedWorks()
	{
		return $this->belongsToMany('App\Models\Work', 'follow_workleaves', 'workleave_id', 'work_id');
	}

	public function scopeActiveWorks($query, $variable)
	{
		return $query->wherehas('followedworks', function($q){$q->active(true);})->with(['followedworks' => function($q){$q->active(true);}]);
	}
}