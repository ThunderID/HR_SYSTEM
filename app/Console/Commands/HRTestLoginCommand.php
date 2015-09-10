<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Console\Commands\Getting;
use App\Console\Commands\Checking;

use Excel, Storage;
use App\Models\Person;

class HRTestLoginCommand extends Command {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:testlogin';

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
		$sheet 				= Excel::load(storage_path('app/password_'.$this->option('example').'.csv'))->toArray();				

		foreach ($sheet as $key => $value) 
		{
			$results 					= $this->dispatch(new Checking(new Person, ['username' => $value['username'], 'password' => $value['password']]));
			$content 					= json_decode($results);

			if(!$content->meta->success)
			{
				$value['message']		= 'Not Exists';
				var_dump($value);
			}
			else
			{
				$results 				= $this->dispatch(new Getting(new Person, ['id' => $content->data->id, 'withattributes' => ['organisation'], 'checkwork' => true], ['created_at' => 'asc'],1, 1));

				$contents 				= json_decode($results);

				if(!$contents->meta->success)
				{
					$value['message']	= 'Expire';
					var_dump($value);
				}
			}
		}
		return true;

		//
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
