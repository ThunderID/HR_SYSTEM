<?php namespace App\Models\Traits\HasMany;

trait HasIdleLogsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasIdleLogsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN PERSON PACKAGE VIA WORK -------------------------------------------------------------------*/
	
	public function IdleLogs()
	{
		return $this->hasMany('App\Models\IdleLog');
	}

	public function ScopeLastIdleLog($query, $variable)
	{
		return $query->whereHas('idlelogs', function($q)use($variable){$q;})
					->with(['idlelogs' => function($q)use($variable){$q->orderBy('updated_at', 'desc');}]);
	}
}