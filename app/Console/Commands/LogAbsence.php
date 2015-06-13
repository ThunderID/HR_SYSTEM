<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DateTime, DateInterval, DatePeriod;
use App\Models\Person;
use App\Models\Log;
use App\Models\ErrorLog;

class LogAbsence extends Command {

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
	public function generatelog($page = 1)
	{
		$per_page 							= 100;

		$begin 								= new DateTime( '- 1 week' );
		$ended 								= new DateTime( 'today'  );

		$interval 							= DateInterval::createFromDateString('1 day');
		$periods 							= new DatePeriod($begin, $interval, $ended);
		foreach ($periods as $key => $value) 
		{
			$search['fullschedule']			= $value->format('Y-m-d');
			$sort 							= ['created_at' => 'desc'];
			$results 						= $this->dispatch(new Getting(new Person, $search, $sort ,(int)$page, $per_page));
			$contents 						= json_decode($results);

			if(!$contents->meta->success)
			{
				App::abort(404);
			}

			$variable 						= json_decode(json_encode($contents->data));

			foreach ($variable as $key2 => $value2) 
			{
				$log['name']				= strtolower('Absence');
				$log['on']					= $value->format('Y-m-d'.' 00:00:00');
				$log['pc']					= 'cron';

				$saved_log 					= $this->dispatch(new Saving(new Log, $log, null, new Person, $value2->id));
				$is_success_2 				= json_decode($saved_log);
				if(!$is_success_2->meta->success)
				{
					$log['email']			= $value2->contacts[0]->value;
					$log['message']			= $is_success_2->meta->errors;
					$saved_error_log 		= $this->dispatch(new Saving(new ErrorLog, $log, null, new Organisation, $value2->organisation_id));
				}
			}
		}

		return true;
	}
}
