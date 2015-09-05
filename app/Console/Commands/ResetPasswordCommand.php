<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Person;
use Log, DB;

class ResetPasswordCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:resetpassword';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset Password pre launch.';

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
		$result 		= $this->resetpassword();

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
	public function resetpassword()
	{
		Log::info('Running Reset Password command @'.date('Y-m-d H:i:s'));

		$persons 					= Person::where('last_password_updated_at', '<', '2015-06-04 00:00:00')->get();

		foreach ($persons as $key => $value) 
		{
			DB::beginTransaction();

			$value->fill([
					'password' 						=> '',
					'last_password_updated_at' 		=> date('Y-m-d H:i:s'),
				]);

			if(!$value->save())
			{
				DB::rollback();

				Log::error('Reset Password '.$value->name.' '.json_encode($value->getError()));
			}
			else
			{
				DB::Commit();
			}
		}

		return true;
	}

}
