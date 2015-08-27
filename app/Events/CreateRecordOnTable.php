<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;

class CreateRecordOnTable extends Event {

	use SerializesModels;

	private $record;
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($record)
	{
		//
		$this->record 				= $record;
	}

	public function getRecord()
	{
		return $this->record;
	}
}
