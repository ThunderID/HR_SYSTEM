<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Organisation;
use App\Models\Workleave;
use App\Models\Queue;
use Log, DB;

class WorkleaveQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:workleavequeue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate queue for batch workleave.';

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
		$result 		= $this->generateworkleave();
		
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
	public function generateworkleave()
	{
		Log::info('Running Progressive Workleave command @'.date('Y-m-d H:i:s'));

		$organisations 				= Organisation::all();

		foreach ($organisations as $key => $value) 
		{
			$follows 				= Workleave::organisationid($value['id'])->status('CN')->activeworks(true)->get();

			foreach ($follows as $key2 => $value2) 
			{
				DB::beginTransaction();

				$parameter['name']			= 'Cuti Progresif Bulan '.date('F');
				$parameter['onstart']		= date('Y-m-d', strtotime('First day of January this year'));
				$parameter['onend']			= date('Y-m-d', strtotime('last day of last month'));
				$parameter['quota']			= $value2['quota'];
				$parameter['workleave_id']	= $value2['id'];
				$parameter['status']		= $value2['status'];
				$parameter['notes']			= 'Auto Generated From Cron Job';

				$queue 						= new Queue;
				$queue->fill([
						'process_name' 			=> 'hr:personworkleavebatch',
						'process_option' 		=> 'personsgiven',
						'parameter' 			=> json_encode($parameter),
						'total_process' 		=> count($value2['followedworks']),
						'task_per_process' 		=> 1,
						'process_number' 		=> 0,
						'total_task' 			=> count($value2['followedworks']),
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
		}

		return true;
	}

}
