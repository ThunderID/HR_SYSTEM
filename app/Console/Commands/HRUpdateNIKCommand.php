<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Person;
use App\Models\Organisation;

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
		$organisation 			= Organisation::get();

		foreach ($organisation as $key => $value) 
		{
			$this->info("Updating ".$value->code);

			$persons 			= Person::organisationid($value->id)->get();
			foreach ($persons as $key2 => $value2) 
			{
				$new_nik 		= str_replace(strtoupper($value2->organisation->code).'.', strtoupper($value2->organisation->code), $value2->uniqid);
				
				$value2->fill(['uniqid' => $new_nik]);

				if (!$value2->save())
				{
					$this->info("Failed ".$value2->getError());
				}

				if(($key2+1)%10==0)
				{
					$this->info("Progress ".($key2+1)." Dari ".count($persons));
				}
			}
		}

		return true;
	}
}
