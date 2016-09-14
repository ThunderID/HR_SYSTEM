<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
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
		$result 		= $this->update13092016();
		
		return true;
	}

	// /**
	//  * Get the console command arguments.
	//  *
	//  * @return array
	//  */
	// protected function getArguments()
	// {
	// 	return [
	// 		['example', InputArgument::REQUIRED, 'An example argument.'],
	// 	];
	// }

	// /**
	//  * Get the console command options.
	//  *
	//  * @return array
	//  */
	// protected function getOptions()
	// {
	// 	return [
	// 		['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
	// 	];
	// }

	/**
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function update13092016()
	{
		Schema::table('tmp_queues', function(Blueprint $table) 
		{
			$table->index(['deleted_at', 'process_number', 'total_process']);
		});

		$this->info("Add index on for queues");

		Schema::table('persons', function(Blueprint $table) 
		{
			$table->index(['deleted_at', 'uniqid']);
		});

		// $this->info("Add index on for person");

		// Schema::table('logs', function(Blueprint $table) 
		// {
		// 	$table->index(['deleted_at', 'created_at', 'on']);
		// });

		// $this->info("Add index id and created_at for logs");

		// Schema::table('error_logs', function(Blueprint $table) 
		// {
		// 	$table->index(['deleted_at', 'created_at', 'on']);
		// });

		// $this->info("Add index on for error logs");

		// Schema::table('charts', function(Blueprint $table) 
		// {
		// 	$table->index(['deleted_at', 'start']);
		// 	$table->index(['deleted_at', 'chart_id', 'end']);
		// });

		// $this->info("Add index on for chart");

		//temporary disabled til release

		// Schema::create('tmp_ip_whitelists', function(Blueprint $table) {
		// 	$table->increments('id');
		// 	$table->string('ip', 255);
		// 	$table->timestamps();
		// 	$table->softDeletes();
		// });
		
		// Schema::create('whitelist_logs', function(Blueprint $table)
		// {
		// 	$table->increments('id');
		// 	$table->string('email', 255);
		// 	$table->string('name', 255);
		// 	$table->string('pc', 255);
		// 	$table->datetime('on');
		// 	$table->text('message');
		// 	$table->string('ip', 255);
		// 	$table->timestamps();
		// 	$table->softDeletes();
			
		// 	$table->index(['deleted_at', 'on']);
		// });
		
		return true;
	}
}
