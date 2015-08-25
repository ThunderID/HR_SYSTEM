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

		$plogs 						= ProcessLog::workorganisationid($parameters['organisation_id'])->ondate($parameters['ondate'])->unsettleattendancelog(null)->get();

		foreach($plogs as $key => $value) 
		{
			DB::beginTransaction();
			
			foreach ($value['attendancelogs'] as $key => $value) 
			{
				$alog  				= AttendanceLog::find($value['id']);
				$alog->fill(['settlement_at' => date('Y-m-d H:i:s'), 'notes' => 'Auto Generated from scheduled job.']);

				if(!$alog->save())
				{
					$errors->add('Queue', $log->getError());
				}
			}

			if(!$errors->count())
			{
				DB::commit();

				$pnumber 						= $pending->process_number+1;
				$pending->fill(['process_number' => $pnumber, 'message' => 'Sedang Menyimpan First Lock Settlement '.(isset($parameters['name']) ? $parameters['name'] : '')]);

				$morphed 						= new QueueMorph;

				$morphed->fill([
					'queue_id'					=> $id,
					'queue_morph_id'			=> $value->id,
					'queue_morph_type'			=> get_class(new AttendanceLog),
				]);

				$morphed->save();
			}
			else
			{
				DB::rollback();
				
				$pending->fill(['message' => json_encode($errors)]);
			}

			$pending->save();
		}

		if($errors->count())
		{
			$pending->fill(['message' => json_encode($errors)]);
		}
		else
		{
			$pending->fill(['process_number' => $pending->total_process, 'message' => 'Sukses Menyimpan First Lock Settlement '.(isset($parameters['name']) ? $parameters['name'] : '')]);
		}

		$pending->save();

		return true;
	}
}
