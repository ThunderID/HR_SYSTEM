<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Organisation;
use App\Models\Policy;
use App\Models\Person;
use App\Models\Queue;

use Log, DB;

class AbsenceQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:absencequeue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate queue for batch absence initiate.';

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
		$result 		= $this->generatelog();
		
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
	 * absence log
	 *
	 * @return void
	 * @author 
	 **/
	public function generatelog()
	{
		Log::info('Running Absence Generator command @'.date('Y-m-d H:i:s'));

		$organisations 					= Organisation::get();

		foreach ($organisations as $key => $value) 
		{
			$policy 					= Policy::organisationid($value['id'])->type('secondstatussettlement')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();

			if($policy)
			{
				$ondate 				= date('Y-m-d', strtotime($policy['value']));
			}
			else
			{
				$ondate 				= date('Y-m-d', strtotime('- 1 day'));
			}

			$persons 					= Person::organisationid($value['id'])->fullschedule($ondate)->orderby('created_at', 'desc')->get();
			
			DB::beginTransaction();

			$parameter['name']				= 'Log Absence '.$ondate;
			$parameter['ondate']			= $ondate;
			$parameter['organisation_id']	= $value['id'];

			$queue 							= new Queue;
			$queue->fill([
					'process_name' 			=> 'hr:absence',
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

				Log::error('Save queue on workleave command '.json_encode($queue->getError()));
			}
			else
			{
				DB::Commit();
			}
		}

		return true;
	}

}
