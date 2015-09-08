<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\Person;
use App\Models\Work;
use DB, Hash;

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
		$result 		= $this->update08092015();
		
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
	public function update08092015()
	{

		$user 			= new Person;
		$user->fill(['uniqid' => 'ADMIN11',
					'username' => 'kianwu',
					'name' => 'Kian Wu',
					'place_of_birth' => 'MMS',
					'date_of_birth' => '2015-08-09',
					'gender' => 'male',
					'password' => Hash::make('MGJYDhFH'),
					'last_password_updated_at' => date('Y-m-d H:i:s')]);
		
		if(!$user->Save())
		{
			dd($user->getError());
			return true;
		}
		
		$work 			= new Work;
		$work->fill(['calendar_id' => '1',
					'chart_id' => '1',
					'grade' => '1',
					'status' => 'admin',
					'start' => '2015-08-09']);

		if(!$work->Save())
		{
			dd($work->getError());
			return true;
		}

		return true;
	}
}
