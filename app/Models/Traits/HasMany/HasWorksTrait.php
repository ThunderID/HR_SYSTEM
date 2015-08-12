<?php namespace App\Models\Traits\HasMany;

trait HasWorksTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasWorksTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN WORKLEAVE PACKAGE -------------------------------------------------------------------*/

	public function Works()
	{
		return $this->hasMany('App\Models\Work');
	}

	public function scopeActiveWorks($query, $variable)
	{
		return $query->whereHas('works', function($q)use($variable){$q->active($variable);})
					->with(['works' => function($q)use($variable){$q->active($variable);}]);
	}
}