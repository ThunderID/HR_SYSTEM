<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Database\Schema\Blueprint;
use Schema;
use App\Models\Calendar;
use App\Models\Chart;
use App\Models\ChartWorkleave;
use App\Models\Workleave;
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
		$result 		= $this->update19092015();
		
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
	public function update19092015()
	{
		Schema::table('works', function(Blueprint $table)
		{	
			$table->boolean('is_absence');
		});

		$this->info("Add boolean is absence on works table");

		Schema::table('tmp_calendars', function(Blueprint $table)
		{	
			$table->text('break_idle');
		});

		$this->info("Add break idle on calendars table");

		Schema::table('tmp_schedules', function(Blueprint $table)
		{	
			$table->double('break_idle');
		});

		$this->info("Add break idle on schedules table");

		Schema::table('person_schedules', function(Blueprint $table)
		{	
			$table->double('break_idle');
		});

		$this->info("Add break idle on person schedules table");

		Schema::table('idle_logs', function(Blueprint $table)
		{	
			$table->double('break_idle');
		});

		$this->info("Add break idle on idle logs table");

		$calendars 							= Calendar::get();

		foreach($calendars as $key => $value)
		{
			$value->fill([
				'break_idle'				=> '3600,3600,3600,3600,5400',
			]);

			if (!$value->save())
			{
				print_r($value->getError());
				exit;
			}
		}

		$this->info("Updating Calendar with break idle");

		Schema::create('charts_workleaves', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('chart_id')->unsigned()->index();
			$table->integer('workleave_id')->unsigned()->index();
			$table->text('rules');
			$table->timestamps();
			$table->softDeletes();
			
			$table->index(['deleted_at', 'chart_id']);
		});

		$this->info("Add charts workleaves table");

		$charts 							= Chart::get();

		foreach($charts as $key => $value)
		{
			$workleave 						= Workleave::organisationid($value->branch->organisation_id)->status('CN')->first();

			if($workleave)
			{
				$data 						= new ChartWorkleave;
				$data->fill([
					'chart_id'				=> $value->id,
					'rules'					=> '- 5 years',
				]);

				$data->Workleave()->associate($workleave);

				if (!$data->save())
				{
					print_r($data->getError());
					exit;
				}
			}
		}

		$this->info("Updating Chart with workleave");

		return true;
	}
}
