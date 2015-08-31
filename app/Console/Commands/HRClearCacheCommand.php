<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class HRClearCacheCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:clearcache';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear cache command.';

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
		$result 		= $this->clearcache();
		
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
	 * clear cache
	 *
	 * @return void
	 * @author 
	 **/
	public function clearcache()
	{
		shell_exec('php artisan cache:clear');
		shell_exec('rm -f ../../../storage/logs/*');

		return true;
	}
}
