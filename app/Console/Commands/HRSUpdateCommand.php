<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\Application;
use App\Models\Menu;
use App\Models\GroupMenu;
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
		$result 		= $this->update27082015();
		
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
	public function update27082015()
	{
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

		return true;
	}
}
