<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use DateTime, DateInterval, DatePeriod, DB;

use \Illuminate\Support\MessageBag as MessageBag;

use App\Models\Person;
use App\Models\Log;
use App\Models\QueueMorph;
use App\Models\Queue;

class LogAbsenceCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:absence';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cron Job : Generated log of absence.';

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

		$result 		= $this->generatelog($id);
		
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
	 * absence log
	 *
	 * @return void
	 * @author 
	 **/
	public function generatelog($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);

		$errors 					= new MessageBag;

		$ondate 					= new DateTime($parameters['ondate']);

		//check work active on that day, please consider if that queue were written days

		$persons 					= Person::organisationid($parameters['organisation_id'])->fullschedule($ondate->format('Y-m-d'))->orderby('created_at', 'desc')->get();
				
		foreach ($persons as $key => $value) 
		{
			$messages 				= json_decode($pending->message, true);

			DB::beginTransaction();

			$log 					= new Log;
			$log->fill([
				'name'				=> strtolower('Absence'),
				'on'				=> $ondate->format('Y-m-d'.' 00:00:00'),
				'last_input_time'	=> $ondate->format('Y-m-d'.' 00:00:00'),
				'app_version'		=> '1.0',
				'ip'				=> getenv("REMOTE_ADDR"),
				'pc'				=> 'cron',
				'person_id'			=> $value['id'],
			]);

			// $log->Person()->associate($value);

			if(!$log->save())
			{
				$errors->add('Queue', $log->getError());
			}

			if(!$errors->count())
			{
				DB::commit();

				$morphed 						= new QueueMorph;

				$morphed->fill([
					'queue_id'					=> $id,
					'queue_morph_id'			=> $log->id,
					'queue_morph_type'			=> get_class(new Log),
				]);

				$morphed->save();
				
				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Sukses Menyimpan Absen '.(isset($value['name']) ? $value['name'] : '');
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}
			else
			{
				DB::rollback();
				
				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Gagal Menyimpan Absen '.(isset($value['name']) ? $value['name'] : '');
				$messages['errors'][$pnumber] 	= $errors;

				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}

			$pending->save();
			
		}

		return true;
	}
}
