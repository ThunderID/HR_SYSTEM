<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',

		'App\Console\Commands\WorkleaveQueueCommand',
		'App\Console\Commands\AbsenceQueueCommand',
		'App\Console\Commands\AttendanceQueueCommand',
		'App\Console\Commands\SanctionQueueCommand',
		'App\Console\Commands\HRQueueCommand',

		'App\Console\Commands\LogAbsenceCommand',
		'App\Console\Commands\FirstLockCommand',
		'App\Console\Commands\SanctionCommand',
		
		'App\Console\Commands\ScheduleBatchCommand',
		'App\Console\Commands\PersonScheduleBatchCommand',
		'App\Console\Commands\PersonWorkleaveBatchCommand',
		'App\Console\Commands\PersonBatchCommand',

		'App\Console\Commands\HRSUpdateCommand',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('hr:absencequeue AbsenceQueueCommand')
				 ->daily();
		$schedule->command('hr:attendancequeue AttendanceQueueCommand')
				 ->cron('0 0 */21 * * *');
		$schedule->command('hr:sanctionqueue SanctionQueueCommand')
				 ->daily();
		 $schedule->command('hr:workleavequeue WorkleaveQueueCommand')
				 ->monthly();
		$schedule->command('hr:queue HRQueueCommand')
				 ->everyFiveMinutes();
	}

}
