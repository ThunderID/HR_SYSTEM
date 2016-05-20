<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Console\Commands\Getting;
use App\Console\Commands\Checking;

use Excel, Storage, Carbon\Carbon as Carbon, Hash;
use App\Models\Person;

class HRUpdateUsernameAndPasswordCommand extends Command {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:updateunamepwd';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$sheet 							= Excel::load(storage_path('app/new_username_password.csv'))->toArray();				

		foreach ($sheet as $key => $value) 
		{
			$errors 					= false;
			//check full biodata
			if(!is_null($value['nik']))
			{
				$person					= Person::uniqid($value['nik'])->first();

				if($person)
				{
					$person->fill(['username' => $value['username'], 'password' => Hash::make($value['password'])]);

					if(!$person->save())
					{
						$errors 				= true;
						$msg					= 'Kary. Nomor : '.($key+1).'  => '.json_decode($person->getError());
						var_dump($msg);
					}
				}
			}
			else
			{
				$errors 				= true;
				$msg					= 'Kary. Nomor : '.($key+1).' [NIK NOT FOUND]';
				var_dump($msg);
			}
		}
		
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

}
