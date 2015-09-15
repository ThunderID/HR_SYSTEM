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
		//update27082015
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

		//update08092015
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

		Schema::table('person_widgets', function(Blueprint $table)
		{	
			$table->integer('organisation_id')->unsigned()->index();
		});

		$this->info("Add org id on person widget table");

		return true;
	}
}
