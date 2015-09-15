<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\Person;
use App\Models\Work;
use App\Models\Policy;
use App\Models\Organisation;
use App\Models\Menu;
use App\Models\GroupMenu;
use App\Models\Application;
use App\Models\ProcessLog;
use App\Models\AttendanceLog;
use App\Models\IdleLog;
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
		$result 		= $this->update10092015();
		
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
	public function update10092015()
	{	
		
		//update1182015
		Schema::table('documents_details', function(Blueprint $table)
		{
			$table->string('string', 255);
			$table->datetime('on');

			$table->index(['deleted_at', 'on']);
			$table->index(['deleted_at', 'numeric']);
		});
		$this->info("Add string and on, on document detail table");

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
			$table->string('process_option', 255);
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

		Schema::create('queue_morphs', function(Blueprint $table) {
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
								'actual_status'				=> $value->actual_status,
								'modified_status'			=> $value->modified_status,
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


		Schema::drop('setting_idles');

		$this->info("Table setting idles removed");

		shell_exec('php artisan db:seed');
		
		$this->info("Seeding template document and template policies");

		//update26082015
		$orgs 										= Organisation::get();
		$types 										= ['asid', 'ulid', 'hcid', 'htid', 'hpid'];
		try
		{
			foreach(range(0, count($orgs)-1) as $index)
			{
				foreach(range(0, count($types)-1) as $key2 => $index2)
				{
					$next1 								= 14 + ($index * 19);
					$next2 								= 15 + ($index * 19);
					$next3 								= 16 + ($index * 19);

					$data 								= new Policy;
					$data->fill([
						'created_by'					=> 1,
						'type'							=> $types[$key2],
						'value'							=> $next1.','.$next2.','.$next3,
						'started_at'					=> date('Y-m-d H:i:s'),
					]);

					$data->organisation()->associate(Organisation::find($orgs[$index]->id));

					if (!$data->save())
					{
						print_r($data->getError());
						exit;
					}
				}
			}
		}
		catch (Exception $e) 
		{
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}	

		//update27082015
		Schema::table('works', function(Blueprint $table)
		{
			$table->string('grade', 255);
		});

		$this->info("Add grade on works table");

		Schema::table('charts', function(Blueprint $table)
		{
			$table->dropColumn('grade');
		});

		$this->info("Drop grade from charts table");

		Schema::create('record_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('parent_id')->unsigned()->index();
			$table->integer('person_id')->unsigned()->index();
			$table->integer('record_log_id')->unsigned()->index();
			$table->string('record_log_type', 255);
			$table->string('name', 255);
			$table->integer('level');
			$table->enum('action', ['save', 'delete', 'restore']);
			$table->text('notes');
			$table->text('old_attributes');
			$table->text('new_attributes');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'person_id']);
			$table->index(['deleted_at', 'record_log_type']);
		});

		$this->info("Add record logs table");

		$app 							= Application::find(1);

		$menus 							= 
											[
												'Lihat Record Log',
												'Tambah Record Log',
												'Ubah Record Log',
												'Hapus Record Log',
											];
		
		$tags 							= 
											[
												'Web Log',
												'Web Log',
												'Web Log',
												'Web Log',
											];

		foreach(range(0, count($menus)-1) as $index)
		{
			$data 								= new Menu;
			$data->fill([
				'name'							=> $menus[$index],
				'tag'							=> $tags[$index],
			]);

			$data->application()->associate($app);

			if (!$data->save())
			{
				print_r($data->getError());
				exit;
			}
		}

		$this->info("Seeded More menu");

		foreach(range(0, 1) as $index)
		{

			foreach(range(102, 105) as $index2)
			{
				$menu 								= Menu::find($index2);
				$data 								= new GroupMenu;
				$data->fill([
					'tmp_auth_group_id'				=> $index+1,
				]);

				$data->menu()->associate($menu);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}
		}

		$this->info("Seeded More auth group");

		shell_exec('php artisan db:seed');

		$this->info("Seeded Document");


		$user 			= new Person;
		$user->fill(['uniqid' => 'ADMINMMS',
					'username' => 'kianwun',
					'name' => 'Kian Wun',
					'place_of_birth' => 'MMS',
					'date_of_birth' => '2015-08-09',
					'gender' => 'male',
					'password' => Hash::make('MGJYDhFH'),
					'last_password_updated_at' => date('Y-m-d H:i:s')]);
		
		$user->Organisation()->associate(Organisation::find(1));

		if(!$user->Save())
		{
			dd($user->getError());
			return true;
		}
		
		$work 			= new Work;
		$work->fill(['calendar_id' => '1',
					'chart_id' => '1',
					'grade' => '1',
					'status' => 'admin',
					'start' => '2015-08-09']);

		$work->person()->associate($user);
		if(!$work->Save())
		{
			dd($work->getError());
			return true;
		}

		return true;
	}
}
