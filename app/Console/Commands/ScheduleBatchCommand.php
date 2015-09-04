<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Queue;
use App\Models\Calendar;
use App\Models\Schedule;
use App\Models\QueueMorph;

use DB;

use \Illuminate\Support\MessageBag as MessageBag;

class ScheduleBatchCommand extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:schedulebatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Schedule Command.';

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
		$id 			= $this->argument()['queueid'];

		if($this->option('queuefunc'))
		{
			$queuefunc 	= $this->option('queuefunc');
			switch ($queuefunc) 
			{
				case 'delete':
					$result = $this->batchDeleteSchedulefromwork($id);
					break;
			
				default:
					$result = $this->batchSchedulefromwork($id);
					break;
			}
		}
		else
		{
			$result 		= $this->batchSchedulefromwork($id);
		}

		return $result;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['queueid', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
            array('queuefunc', null, InputOption::VALUE_OPTIONAL, 'Queue Function', null),
        );
	}

	/**
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchSchedulefromwork($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);
		$messages 					= json_decode($pending->message, true);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$data 						= Schedule::ondate([$parameters['on'], date('Y-m-d', strtotime($parameters['on'].' + 1 day'))])->calendarid($parameters['associate_calendar_id'])->first();
		if($data)
		{
			$is_success				= $data;
		}
		else
		{
			$is_success				= new Schedule;
		}
		
		$calendar 					= Calendar::find($parameters['associate_calendar_id']);

		$is_success->fill($parameters);
		$is_success->Calendar()->associate($calendar);

		if(!$is_success->save())
		{
			$errors->add('Batch', $is_success->getError());
		}

		if(!$errors->count())
		{
			$morphed 						= new QueueMorph;
			$morphed->fill([
				'queue_id'					=> $id,
				'queue_morph_id'			=> $is_success->id,
				'queue_morph_type'			=> get_class(new Schedule),
			]);
			$morphed->save();

			$pnumber 						= $pending->process_number+1;
			$messages['message'][$pnumber] 	= 'Sukses Menyimpan Jadwal '.(isset($calendar['name']) ? $calendar['name'] : '');
			$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
		}
		else
		{
			$pnumber 						= $pending->process_number+1;
			$messages['message'][$pnumber] 	= 'Gagal Menyimpan Jadwal '.(isset($calendar['name']) ? $calendar['name'] : '');
			$messages['errors'][$pnumber] 	= $errors;

			$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
		}

		$pending->save();

		return true;

	}

	/**
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchDeleteSchedulefromwork($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);
		$messages 					= json_decode($pending->message, true);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$data 						= Schedule::id($parameters['id'])->withattributes(['calendar'])->first();
		$calendar 					= $data['calendar'];

		DB::beginTransaction();

		if(!$data)
		{
			$errors->add('Batch', 'Data tidak tersedia');
		}

		if(!$errors->count() && !$data->delete())
		{
			$errors->add('Batch', $data->getError());
		}

		if(!$errors->count())
		{
			DB::commit();

			$pnumber 						= $pending->process_number+1;
			$messages['message'][$pnumber] 	= 'Sukses Menghapus Jadwal '.(isset($calendar['name']) ? $calendar['name'] : '');
			$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
		}
		else
		{
			DB::rollback();
			
			$pnumber 						= $pending->process_number+1;
			$messages['message'][$pnumber] 	= 'Gagal Menghapus Jadwal '.(isset($calendar['name']) ? $calendar['name'] : '');
			$messages['errors'][$pnumber] 	= $errors;

			$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
		}

		$pending->save();

		return true;
	}
}
