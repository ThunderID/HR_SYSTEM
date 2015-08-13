<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Queue;
use App\Models\Calendar;
use App\Models\Schedule;
use \Illuminate\Support\MessageBag as MessageBag;

class ScheduleBatchCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:schedulebatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Schedule Command.';

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
		$id 			= $this->option('queueid');

		$result 		= $this->batchSchedulefromwork($id);

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
			['argument', InputArgument::REQUIRED, 'An example argument.'],
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
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchSchedulefromwork($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= (array)json_decode($pending->parameter);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$data 						= Schedule::ondate([$parameters['on'], date('Y-m-d', strtotime($parameters['on'].' + 1 day'))])->calendarid($parameters['associate_calendar_id'])->first();
		if($data)
		{
			$parameters['id']		= $data->id;
		}

		$content 					= $this->dispatch(new Saving(new Schedule, $parameters, (isset($parameters['id']) ? $parameters['id'] : null), new Calendar, $parameters['associate_calendar_id']));

		$is_success 				= json_decode($content);
		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Batch', $value2);
					}
				}
				else
				{
					$errors->add('Batch', $value);
				}
			}
		}

		if(!$errors->count())
		{
			$pending->fill(['message' => 'Success', 'process_number' => 1]);		
		}
		else
		{
			$pending->fill(['message' => json_encode($errors)]);
		}

		$pending->save();

		return true;

	}

}
