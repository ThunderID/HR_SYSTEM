<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Organisation;
use App\Models\Policy;
use App\Models\ProcessLog;
use App\Models\Queue;

use Log, DB;

class AttendanceQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:attendancequeue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate queue for batch attendance settlement.';

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
		$result 		= $this->generateattendance();
		
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
	 * lock around 20 - next 20
	 *
	 * @return void
	 * @author 
	 **/
	public function generateattendance()
	{
		Log::info('Running First Lock command @'.date('Y-m-d H:i:s'));

		$organisations 						= Organisation::get();

		foreach ($organisations as $key => $value) 
		{
			$policy 						= Policy::organisationid($value['id'])->type('firststatussettlement')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
				
			if($policy)
			{
				$ondate 					= [date('Y-m-d', strtotime($policy['value'])), date('Y-m-d')];
			}
			else
			{
				$ondate 					= [date('Y-m-d', strtotime('- 1 month')), date('Y-m-d')];
			}

			$plogs 							= ProcessLog::workorganisationid($value['id'])->ondate($ondate)->unsettleattendancelog(null)->get();

			DB::beginTransaction();

			$parameter['name']				= 'Lock Attendance '.$ondate[0].' - '.$ondate[1];
			$parameter['ondate']			= $ondate;
			$parameter['organisation_id']	= $value['id'];

			$queue 							= new Queue;
			$queue->fill([
					'process_name' 			=> 'hr:firstlock',
					'parameter' 			=> json_encode($parameter),
					'total_process' 		=> count($plogs),
					'task_per_process' 		=> 1,
					'process_number' 		=> 0,
					'total_task' 			=> count($plogs),
					'message' 				=> 'Initial Commit',
			]);

			if(!$queue->save())
			{
				DB::rollback();

				Log::error('Save queue on firstlock command '.json_encode($queue->getError()));
			}
			else
			{
				DB::Commit();
			}
		}
		return true;
	}
}
