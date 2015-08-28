<?php namespace App\Models\Traits\HasMany;

use DateTime;

trait HasRecordLogsTrait {

	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/

	function HasRecordLogsTraitConstructor()
	{
		//
	}

	/* ------------------------------------------------------------------- RELATIONSHIP IN ORGANISATION PACKAGE -------------------------------------------------------------------*/

	public function RecordLogs()
	{
		return $this->hasMany('App\Models\RecordLog');
	}

	public function Childs()
	{
		return $this->hasMany('App\Models\RecordLog', 'parent_id');
	}
}
