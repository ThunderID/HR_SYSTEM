<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Organisation;
use App\Models\Person;
use App\Models\Queue;

use Log, DB;
use \Illuminate\Support\MessageBag as MessageBag;

class HRRaiseWorkleaveQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:raiseworkleavequeue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Add raise quota function for workleave.';

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
		$result 		= $this->raiseworkleavequeue();

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
			['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

	/**
	 * Update Expired Workleave
	 *
	 * @return void
	 * @author 
	 **/
	public function raiseworkleavequeue()
	{
		Log::info('Running Raise Workleave Queue command @'.date('Y-m-d H:i:s'));

		$organisations 						= Organisation::all();

		foreach ($organisations as $key => $value) 
		{
			$persons						= Person::organisationid($value['id'])->checkwork(true)->get();
			
			DB::beginTransaction();

			$parameter['end']				= date('Y-m-d', strtotime('first day of January this year'));
			$parameter['organisation_id']	= $value->id;

			$queue 							= new Queue;
			$queue->fill([
					'process_name' 			=> 'hr:raiseworkleavebatch',
					'parameter' 			=> json_encode($parameter),
					'total_process' 		=> count($persons),
					'task_per_process' 		=> 1,
					'process_number' 		=> 0,
					'total_task' 			=> count($persons),
					'message' 				=> 'Initial Commit',
				]);

			if(!$queue->save())
			{
				DB::rollback();

				Log::error('Save queue of expiring workleave command '.json_encode($queue->getError()));
			}
			else
			{
				DB::Commit();
			}
		}

		return true;
	}
}
