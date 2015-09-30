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
		'App\Console\Commands\HRExpiredWorkleaveQueueCommand',
		'App\Console\Commands\HRRaiseWorkleaveQueueCommand',

		'App\Console\Commands\LogAbsenceCommand',
		'App\Console\Commands\FirstLockCommand',
		'App\Console\Commands\SanctionCommand',
		
		'App\Console\Commands\ScheduleBatchCommand',
		'App\Console\Commands\PersonScheduleBatchCommand',
		'App\Console\Commands\PersonWorkleaveBatchCommand',
		'App\Console\Commands\PersonBatchCommand',
		'App\Console\Commands\PersonDocumentBatchCommand',
		'App\Console\Commands\WorkBatchCommand',
		'App\Console\Commands\ExpiredWorkleaveBatchCommand',
		'App\Console\Commands\RaiseWorkleaveBatchCommand',

		'App\Console\Commands\HRClearCacheCommand',
		'App\Console\Commands\HRSUpdateCommand',
		'App\Console\Commands\ResetPasswordCommand',
		'App\Console\Commands\HRResetTableCommand',
		'App\Console\Commands\HRUpdateNIKCommand',
		'App\Console\Commands\HRTestLoginCommand',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//write queue

		//auto generate log for previous day (or) previous day : as sanction
		$schedule->command('hr:absencequeue AbsenceQueueCommand')
				 ->dailyAt('06:00');

		//auto generate log for previous day 
		$schedule->command('hr:attendancequeue AttendanceQueueCommand')
				 ->cron('0 */6 */26 * * *');

		//auto generate sanction for previous day (currently 5 days)
		$schedule->command('hr:sanctionqueue SanctionQueueCommand')
				 ->dailyAt('20:00');

		//auto generate progressive workleave for previous month
		$schedule->command('hr:workleavequeue WorkleaveQueueCommand')
				 ->cron('0 0 */15 * * *');

		//running queue (every five minutes)
		$schedule->command('hr:queue HRQueueCommand')
				 ->everyFiveMinutes();

		//running queue (every month)
		$schedule->command('hr:clearcache HRClearCacheCommand')
				 ->cron('0 */21 */25 * * *');

		//running expired queue command
		$schedule->command('hr:expireworkleavequeue HRExpiredWorkleaveQueueCommand')
				 ->cron('0 */4 */28 * * *');

		//running expired queue command
		$schedule->command('hr:raiseworkleavequeue HRRaiseWorkleaveQueueCommand')
				 ->cron('0 */22 */27 * * *');
	}

}
