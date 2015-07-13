<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
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
		//
		$result 		= $this->update1372015();
		
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
	public function update1372015()
	{
		DB::statement("ALTER TABLE works MODIFY COLUMN status ENUM('contract', 'probation', 'internship', 'permanent', 'others' ,'admin')");

		$this->info("Alter works status to 'contract', 'probation', 'internship', 'permanent', 'others', 'admin'");

		Schema::drop('authentications');

		$this->info("Table Authentications removed");

		Schema::create('works_authentications', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('tmp_auth_group_id')->unsigned()->index();
			$table->integer('organisation_id')->unsigned()->index();
			$table->integer('work_id')->unsigned()->index();
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Table Work Authentications created");

		Schema::create('tmp_auth_groups', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Table Auth Groups created");

		Schema::create('tmp_groups_menus', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('tmp_auth_group_id')->unsigned()->index();
			$table->integer('tmp_menu_id')->unsigned()->index();
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Table Groups Menus created");

		Schema::table('tmp_menus', function($table)
		{
			$table->string('tag', 255);
			$table->text('description');
		});

		$this->info("Add tag and description on menus table");

		Schema::table('logs', function($table)
		{
			$table->datetime('last_input_time')->nullable();
		});

		$this->info("Add last input time on logs table");

		return true;
	}
}
