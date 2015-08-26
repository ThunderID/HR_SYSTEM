<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\Organisation;
use App\Models\Policy;
use DB;

class HRSUpdateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update HR System.';

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
		//Also moved process log to older process log
		$result 		= $this->update26082015();
		
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
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function update26082015()
	{
		$orgs 										= Organisation::get();
		$types 										= ['asid', 'ulid', 'hcid', 'htid', 'hpid'];
		try
		{
			foreach(range(0, count($orgs)-1) as $index)
			{
				foreach(range(0, count($types)-1) as $key2 => $index2)
				{
					$next1 								= 14 + ($index * 19);
					$next2 								= 15 + ($index * 19);
					$next3 								= 16 + ($index * 19);

					$data 								= new Policy;
					$data->fill([
						'created_by'					=> 1,
						'type'							=> $types[$key2],
						'value'							=> $next1.','.$next2.','.$next3,
						'started_at'					=> date('Y-m-d H:i:s'),
					]);

					$data->organisation()->associate(Organisation::find($orgs[$index]->id));

					if (!$data->save())
					{
						print_r($data->getError());
						exit;
					}
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	

		return true;
	}
}
