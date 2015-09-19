<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Person;

class HRUpdateNIKCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:updatenik';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update NIK format from AJR.YEAR.NUMBER to AJRYEAR.NUMBER.';

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
		$result 		= $this->updatenik();

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
	 * UPDATE NIK FORMAT
	 *
	 * @return void
	 * @author 
	 **/
	public function updatenik()
	{
		$persons 				= Person::get();

		foreach ($persons as $key => $value) 
		{
			str_replace(strtoupper($value->organisation->code).'.', strtoupper($value->organisation->code), $value->uniqid);
			if (!$value->save())
			{
				print_r($value->getError());
				exit;
			}
		}

		return true;
	}
}
