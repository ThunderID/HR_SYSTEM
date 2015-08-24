<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Console\Commands\Getting;

use App\Models\Organisation;
use App\Models\Policy;
use App\Models\ProcessLog;
use App\Models\AttendanceLog;

use Log, DB;

class FirstLockCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

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
		$result 		= $this->firstlock();
		
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
	 * lock aroung 20 - next 20
	 *
	 * @return void
	 * @author 
	 **/
	public function firstlock()
	{
		Log::info('Running First Lock command @'.date('Y-m-d H:i:s'));

		$search						= [];
		$sort						= [];
		$results 					= $this->dispatch(new Getting(new Organisation, $search, $sort ,1, 100));
		$contents 					= json_decode($results);

		if(!$contents->meta->success)
		{
			return true;
		}

		$organisations 				= json_decode(json_encode($contents->data), true);

		foreach ($organisations as $key => $value) 
		{
			unset($search);
			unset($sort);
			$search['type']				= 'firststatussettlement';
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at', 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$settlementdate 		= json_decode(json_encode($contents->data), true);

				unset($search);
				unset($sort);
				$date 						= date('Y-m-d', strtotime('- 2 months'));
				$search['unsettleattendancelog']= null;
				$search['organisationid']	= $value['id'];
				$search['ondate']			= [$date, date('Y-m-d')];
				$sort						= ['created_at', 'desc'];

				$results 					= $this->dispatch(new Getting(new ProcessLog, $search, $sort ,1, 100));
				$contents 					= json_decode($results);

				if($contents->meta->success)
				{
					$plogs 					= json_decode(json_encode($contents->data), true);

					DB::beginTransaction();
					foreach($plogs as $key2 => $value2) 
					{
						foreach ($value2['attendancelogs'] as $key3 => $value3) 
						{
							$alog  				= AttendanceLog::find($value3['id']);
							$alog->fill(['settlement_at' => date('Y-m-d H:i:s')]);

							if(!$alog->save())
							{
								DB::rollback();
								Log::error(json_encode($alog->getError()));
							}
						}
					}
					DB::commit();
				}
			}
		}
		return true;
	}
}
