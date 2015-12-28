<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Organisation;
use App\Models\Policy;
use App\Models\Person;
use App\Models\Queue;

use Log, DB;

class LogObserverQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:logobservequeue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate queue for log observer.';

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
		Log::info('Running LogObserver Generator command @'.date('Y-m-d H:i:s'));

		$organisations 					= Organisation::get();

		foreach ($organisations as $key => $value) 
		{
			$on 						= date('Y-m-d H:i:s', strtotime('- 1 day'));

			$idle_rule 					= new Policy;
			$idle_rule_1 				= $idle_rule->organisationid($value['id'])->type('firstidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			$idle_rule_2 				= $idle_rule->organisationid($value['id'])->type('secondidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			$idle_rule_3 				= $idle_rule->organisationid($value['id'])->type('thirdidle')->OnDate($on)->orderBy('started_at', 'desc')->first();
			
			$margin_bottom_idle 		= 900;
			$idle_1 					= 3600;
			$idle_2 					= 7200;

			if($idle_rule_1)
			{
				$margin_bottom_idle = (int)$idle_rule_1->value;
			}
			if($idle_rule_2)
			{
				$idle_1 			= (int)$idle_rule_2->value;
			}
			if($idle_rule_3)
			{
				$idle_2 			= (int)$idle_rule_3->value;
			}


			$persons 					= Person::organisationid($value['id'])->checkwork(true)->count();

			if($persons > 0)
			{
				DB::beginTransaction();

				$parameter['margin_bottom_idle']= $margin_bottom_idle;
				$parameter['idle_1']			= $idle_1;
				$parameter['idle_2']			= $idle_2;
				$parameter['on']				= $on;

				$parameter['organisation_id']	= $value['id'];

				$queue 							= new Queue;
				$queue->fill([
						'process_name' 			=> 'hr:logobserver',
						'parameter' 			=> json_encode($parameter),
						'total_process' 		=> $persons,
						'task_per_process' 		=> 1,
						'process_number' 		=> 0,
						'total_task' 			=> $persons,
						'message' 				=> 'Initial Commit',
				]);

				if(!$queue->save())
				{
					DB::rollback();

					Log::error('Save queue on LogObserver command '.json_encode($queue->getError()));
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
