<?php namespace App\Handlers\Events;

use App\Events\CreateRecordOnTable;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use App\Models\RecordLog;

class CreatedRecord {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  CreateRecordOnTable  $event
	 * @return void
	 */
	public function handle(CreateRecordOnTable $event)
	{
		//
		$record 						= new RecordLog;
		$attributes 					= $event->getRecord();

		if(isset($attributes['person_id']) && Session::has('loggedUser') && Session::get('loggedUser')==$attributes['person_id'])
		{
			$attributes['level'] 		= Session::Get('user.menuid');
		}
		elseif(!isset($attributes['person_id']) && Session::has('loggedUser'))
		{
			$attributes['person_id'] 	= Session::get('loggedUser');
		}

		$record->fill($attributes);

		if($record->save())
		{
			return true;
		}

		Log::error('Error recording log '.json_encode($record->getError()));

		return true;
	}

}
