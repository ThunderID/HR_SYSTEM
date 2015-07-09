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
		$result 		= $this->update972015();
		
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
	public function update972015()
	{
		Schema::table('tmp_workleaves', function($table)
		{
			$table->enum('status', ['CB', 'CN', 'CI']);
			$table->boolean('is_active');
		});

		$this->info("Add status, is active on tmp workleaves table");

		DB::statement("ALTER TABLE person_workleaves MODIFY COLUMN status ENUM('OFFER', 'CB', 'CN', 'CI', 'CONFIRMED')");

		Schema::table('person_workleaves', function($table)
		{
			$table->integer('workleave_id')->unsigned()->index();
		});

		$this->info("Alter person workleaves status to 'OFFER', 'CB', 'CN', 'CI', 'CONFIRMED' add workleave id add field workleave id");

		Schema::table('tmp_calendars', function($table)
		{
			$table->integer('import_from_id')->unsigned()->index();
		});

		$this->info("Add Calendar Parent");

		DB::statement("ALTER TABLE tmp_schedules MODIFY COLUMN status ENUM('DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'HB', 'L')");

		$this->info("Alter tmp_schedules status to 'DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'HB', 'L");
	

		DB::statement("ALTER TABLE person_schedules MODIFY COLUMN status ENUM('DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'HB', 'L')");

		$this->info("Alter person_schedules status to 'DN', 'SS', 'SL', 'CN', 'CB', 'CI', 'UL', 'HB', 'L");

		//update772015
		// DB::statement("ALTER TABLE works MODIFY COLUMN status ENUM('contract', 'probation', 'internship', 'permanent', 'others')");

		// $this->info("Alter works status to 'contract', 'probation', 'internship', 'permanent', 'others'");

		// Schema::drop('persons_workleaves');

		// $this->info("Table Persons Workleaves removed");

		// Schema::table('apis', function($table)
		// {
		//     $table->string('workstation_address', 255);
		// 	$table->string('workstation_name', 255);
		// 	$table->string('tr_version', 255);
		// 	$table->boolean('is_active');
		// });

		// $this->info("Add workstation address, workstation name and tracker version on apis table");

		// Schema::create('person_workleaves', function(Blueprint $table)
		// {
		// 	$table->increments('id');
		// 	$table->integer('person_id')->unsigned()->index();
		// 	$table->integer('work_id')->unsigned()->index();
		// 	$table->integer('person_workleave_id')->unsigned()->index();
		// 	$table->integer('created_by')->unsigned()->index();
		// 	$table->string('name', 255);
		// 	$table->date('start');
		// 	$table->date('end');
		// 	$table->integer('quota');
		// 	$table->enum('status', ['offer', 'annual', 'special', 'confirmed']);
		// 	$table->text('notes');
		// 	$table->timestamps();
		// 	$table->softDeletes();
			
		// 	$table->index(['deleted_at', 'person_id', 'start']);
		// });

		// $this->info("Table Person Workleaves created");

		return true;
	}
}
