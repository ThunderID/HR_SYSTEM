<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Illuminate\Support\MessageBag as MessageBag;

use App\Models\QueueMorph;
use App\Models\Queue;
use App\Models\ProcessLog;
use App\Models\AttendanceLog;

use Log, DB;

class FirstLockCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:firstlock';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generated Lock for Log.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		$id 			= $this->argument()['queueid'];

		$result 		= $this->firstlock($id);
		
		return true;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['queueid', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
            array('queueid', null, InputOption::VALUE_OPTIONAL, 'Queue ID', null),
        );
	}

	/**
	 * lock aroung 20 - next 20
	 *
	 * @return void
	 * @author 
	 **/
	public function firstlock($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);

		$errors 					= new MessageBag;

		$plogs 						= ProcessLog::workorganisationid($parameters['organisation_id'])->ondate($parameters['ondate'])->unsettleattendancelog(null)->withattributes(['person'])->get();

		foreach($plogs as $key => $value) 
		{
			$messages 				= json_decode($pending->message, true);

			DB::beginTransaction();
			
			foreach ($value['attendancelogs'] as $key2 => $value2) 
			{
				$alog  				= AttendanceLog::find($value2['id']);
				$alog->fill(['settlement_at' => date('Y-m-d H:i:s'), 'notes' => 'Auto Generated from scheduled job.']);

				if(!$alog->save())
				{
					$errors->add('Queue', $log->getError());
				}
			}

			if(!$errors->count())
			{
				DB::commit();

				$morphed 						= new QueueMorph;

				$morphed->fill([
					'queue_id'					=> $id,
					'queue_morph_id'			=> $value->id,
					'queue_morph_type'			=> get_class(new AttendanceLog),
				]);

				$morphed->save();

				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Sukses Menyimpan Settlement '.(isset($value['on']) ? $value['person']['name'].' Pada '.$value['on'] : '');
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}
			else
			{
				DB::rollback();
				
				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Gagal Menyimpan Settlement '.(isset($value['on']) ? $value['person']['name'].' Pada '.$value['on'] : '');
				$messages['errors'][$pnumber] 	= $errors;
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}

			$pending->save();
		}

		return true;
	}
}
