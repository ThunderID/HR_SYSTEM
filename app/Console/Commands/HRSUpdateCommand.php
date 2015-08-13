<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\ProcessLog;
use App\Models\AttendanceLog;
use App\Models\IdleLog;
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
		//Also moved process log to older process log
		$result 		= $this->update11082015();
		
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
	public function update11082015()
	{
		Schema::create('idle_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('process_log_id')->unsigned()->index();
			$table->double('total_active');
			
			$table->double('total_idle');
			$table->double('total_idle_1');
			$table->double('total_idle_2');
			$table->double('total_idle_3');

			$table->integer('frequency_idle_1');
			$table->integer('frequency_idle_2');
			$table->integer('frequency_idle_3');

			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at']);
		});

		$this->info("Add idle logs table");

		Schema::create('attendance_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('process_log_id')->unsigned()->index();
			$table->integer('settlement_by')->unsigned()->index();
			$table->integer('modified_by')->unsigned()->index();
			$table->string('actual_status', 255);
			$table->string('modified_status', 255);

			$table->double('margin_start');
			$table->double('margin_end');

			$table->double('tolerance_time');
			
			$table->double('count_status');
			$table->text('notes');
			$table->datetime('modified_at')->nullable();
			$table->datetime('settlement_at')->nullable();

			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at']);
		});
		
		$this->info("Add attendance logs table");

		Schema::create('attendance_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('attendance_log_id')->unsigned()->index();
			$table->integer('person_workleave_id')->unsigned()->index();
			$table->integer('person_document_id')->unsigned()->index();
			$table->string('person_workleave_type', 255);
			$table->string('person_document_type', 255);
			$table->timestamps();
			$table->softDeletes();

			$table->index(['deleted_at', 'person_workleave_id']);
			$table->index(['deleted_at', 'person_document_id']);
		});

		$this->info("Add attendance details table");

		Schema::create('follow_workleaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('workleave_id')->unsigned()->index();
			$table->integer('work_id')->unsigned()->index();
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at']);
		});

		$this->info("Add follow workleaves table");

		Schema::create('marital_statuses', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('person_id')->unsigned()->index();
			$table->string('status', 255);
			$table->datetime('on');
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Add marital statuses table");

		Schema::create('tmp_policies', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('organisation_id')->unsigned()->index();
			$table->integer('created_by')->unsigned()->index();
			$table->string('type', 255);
			$table->text('value');
			$table->datetime('started_at');
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Add policies table");

		Schema::create('tmp_queues', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('created_by')->unsigned()->index();
			$table->string('process_name', 255);
			$table->text('parameter');
			$table->integer('total_process');
			$table->integer('task_per_process');
			$table->integer('process_number');
			$table->integer('total_task');
			$table->text('message');
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Add queues table");

		Schema::create('queues_morphs', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('queue_id')->unsigned()->index();
			$table->integer('queue_morph_id')->unsigned()->index();
			$table->string('queue_morph_type', 255);
			$table->timestamps();
			$table->softDeletes();
		});

		$this->info("Add queues morphs table");

		//migrating processlog to attendance log and idle log
		$plogs 							= ProcessLog::all();

		DB::beginTransaction();
		foreach ($plogs as $key => $value) 
		{
			$alog 						= new AttendanceLog;
			$ilog 						= new IdleLog;

			$alog->fill([
								'margin_start'				=> $value->margin_start,
								'margin_end'				=> $value->margin_end,
								//working on count status
								'count_status'				=> 1,
								'actual_status'				=> $value->actual_status,
			]);

			$ilog->fill([
								'total_active'				=> $value->total_active,
								'total_idle'				=> $value->total_idle,
								'total_idle_1'				=> $value->total_idle_1,
								'total_idle_2'				=> $value->total_idle_2,
								'total_idle_3'				=> $value->total_idle_3,
								'frequency_idle_1'			=> $value->frequency_idle_1,
								'frequency_idle_2'			=> $value->frequency_idle_2,
								'frequency_idle_3'			=> $value->frequency_idle_3,
			]);

			$plog 						= ProcessLog::find($value->id);

			$alog->ProcessLog()->associate($plog);
			$ilog->ProcessLog()->associate($plog);

			if (!$alog->save())
			{
				DB::rollback();
				dd($alog->getError());

				return false;
			}

			if (!$ilog->save())
			{
				DB::rollback();
				dd($ilog->getError());

				return false;
			}

			if($key%100==0)
			{
				$this->info("Chunked P.log ".$key.' From '.count($plogs));
			}
		}

		DB::commit();

		$this->info("Chunked P.log ");

		Schema::table('process_logs', function(Blueprint $table)
		{
			$table->dropColumn('margin_start');
			$table->dropColumn('margin_end');
			$table->dropColumn('modified_by');
			$table->dropColumn('total_idle');
			$table->dropColumn('total_idle_1');
			$table->dropColumn('total_idle_2');
			$table->dropColumn('total_idle_3');
			$table->dropColumn('frequency_idle_1');
			$table->dropColumn('frequency_idle_2');
			$table->dropColumn('frequency_idle_3');
			$table->dropColumn('total_sleep');
			$table->dropColumn('total_active');
			$table->dropColumn('actual_status');
			$table->dropColumn('modified_status');
			$table->dropColumn('tolerance_time');
			$table->dropColumn('modified_at');
		});

		$this->info("Remove modified_by, total_idle, total_idle_1, total_idle_2, total_idle_3, frequency_idle_1, frequency_idle_2, frequency_idle_3, total_sleep, total_active, actual_status, modified_status, tolerance_time, modified_at From Process Log");
		
		Schema::table('persons', function(Blueprint $table)
		{
			$table->datetime('last_password_updated_at');
		});

		$this->info("Add last_password_updated_at on persons table");

		Schema::table('person_widgets', function(Blueprint $table)
		{
			$table->dropColumn('title');
			$table->dropColumn('type');
			$table->dropColumn('field');
			$table->dropColumn('function');
		});

		Schema::table('person_widgets', function(Blueprint $table)
		{
			$table->enum('type', ['list', 'table', 'stat']);
			$table->string('widget', 255);
			$table->string('dashboard', 255);
			$table->boolean('is_active');
		});

		$this->info("Add widget, dashboard, is_active, drop title, field and function on person widgets table");

		return true;
	}
}
