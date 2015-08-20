<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DateTime, DateInterval, DatePeriod;
use App\Models\Person;
use App\Models\Log;
use App\Models\ErrorLog;

class LogAbsenceCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;
	
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
		$search						= [];
		$sort						= [];
		$results 					= $this->dispatch(new Getting(new Organisation, $search, $sort ,1, 100));
		$contents 					= json_decode($results);

		if(!$contents->meta->success)
		{
			return true;
		}

		$organisations 				= json_decode(json_encode($contents->data));

		foreach ($organisations as $key => $value) 
		{
			unset($search);
			unset($sort);
			$search['type']				= 'secondstatussettlement';
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at', 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$settlementdate 		= json_decode(json_encode($contents->data));

				unset($search);
				unset($sort);

				$search['organisationid']		= $value['id']);
				$search['fullschedule']			= date('Y-m-d', strtotime($settlementdate['value']));
				$sort 							= ['created_at' => 'desc'];
				$results 						= $this->dispatch(new Getting(new Person, $search, $sort ,1, 100));
				$contents 						= json_decode($results);

				if($contents->meta->success))
				{
					$persons 						= json_decode(json_encode($contents->data));

					foreach ($persons as $key2 => $value2) 
					{
						$log['name']				= strtolower('Absence');
						$log['on']					= $value->format('Y-m-d'.' 00:00:00');
						$log['last_input_time']		= $value->format('Y-m-d'.' 00:00:00');
						$log['app_version']			= '1.0';
						$log['ip']					= getenv("REMOTE_ADDR");
						$log['pc']					= 'cron';

						$saved_log 					= $this->dispatch(new Saving(new Log, $log, null, new Person, $value2->id));
						$is_success_2 				= json_decode($saved_log);
						if(!$is_success_2->meta->success)
						{
							$log['email']			= $value2->username;
							$log['message']			= $is_success_2->meta->errors;
							$saved_error_log 		= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $value['id']));
						}
					}
				}
			}
		}

		return true;
	}
}
