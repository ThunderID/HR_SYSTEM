<?php namespace App\Models\Traits\BelongsTo;

trait HasApplicationTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasApplicationTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN CHAUTH PACKAGE -------------------------------------------------------------------*/

	public function Application()
	{
		return $this->belongsTo('App\Models\Application', 'application_id');
	}

	public function scopeHasApplication($query, $variable)
	{
		return $query->whereHas('application', function($q)use($variable){$q;});
	}

	public function scopeApplicationID($query, $variable)
	{
		return $query->whereHas('application', function($q)use($variable){$q->id($variable);});
	}
}