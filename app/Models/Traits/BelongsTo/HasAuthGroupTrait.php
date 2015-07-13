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
		return $this->belongsTo('App\Models\AuthGroup', 'tmp_auth_group_id');
	}

	public function scopeAuthGroupID($query, $variable)
	{
		return $query->whereHas('authgroup', function($q)use($variable){$q->id($variable);});
	}

	public function scopeAccessMenuID($query, $variable)
	{
		return $query->whereHas('authgroup.menus', function($q)use($variable){$q->id($variable);});
	}
}