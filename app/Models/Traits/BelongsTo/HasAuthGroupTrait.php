<?php namespace App\Models\Traits\BelongsTo;

trait HasAuthGroupTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasAuthGroupTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function AuthGroup()
	{
		return $this->belongsTo('App\Models\AuthGroup');
	}

	public function scopeAuthGroupID($query, $variable)
	{
		return $query->whereHas('tmp_auth_groups', function($q)use($variable){$q->id($variable);});
	}
}