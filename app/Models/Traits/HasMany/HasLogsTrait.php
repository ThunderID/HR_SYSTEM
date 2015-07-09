<?php namespace App\Models\Traits\HasMany;

use DB;

trait HasLogsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasLogsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN SCHEDULE PACKAGE -------------------------------------------------------------------*/

	public function Logs()
	{
		return $this->hasMany('App\Models\Log');
	}

	public function ScopeLogsOndate($query, $variable)
	{
		return $query->with(['Logs' => function($q)use($variable){$q->ondate($variable['on'])->orderBy('on', 'asc');}]);
	}
}