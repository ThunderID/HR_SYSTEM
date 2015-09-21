<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\FollowWorkleave;
use App\Models\ChartWorkleave;
use App\Models\Work;

use Carbon\Carbon;

use \Illuminate\Support\MessageBag as MessageBag;

class RaiseWorkleaveBatchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:raiseworkleavebatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Raise queue running.';

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

		$result 		= $this->batchraiseworkleave($id);

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
	public function batchraiseworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);
		$messages 					= json_decode($pending->message, true);

		$errors 					= new MessageBag;

		$persons					= Person::organisationid($parameters['organisation_id'])->checkwork(true)->currentwork(true)->get();
		
		foreach ($persons as $key => $value) 
		{
			//cek riwayat kerja
			$work 					= Work::active(true)->personid($value['id'])->first();

			$prev_work 				= Work::active(false)->personid($value['id'])->startbefore($work->start)->orderby('start', 'desc')->get();

			$work_start 			= $work->start;

			foreach ($prev_work as $key2 => $value2) 
			{
				if($key2 > 0)
				{
					$prev_end 		= new Carbon($prev_work[$key2-1]->end);
					$date_diff 		= $prev_end->diffInDays(new Carbon($value2->start));

					//if end of prev work not same day of next start (idle), then start of work must be next start
					if($date_diff > 1)
					{
						$work_start = $value2->start;
					}
					//else if end of prev work is same day of next start (idle), then start of work must be first work start
				}
				else
				{
					$work_start 		= $value2->start;
				}
			}

			$cwleave 					= ChartWorkleave::chartid($work['chart_id'])->withattributes(['workleave'])->first();
			
			if($cwleave && $work_start >= date('Y-m-d', strtotime($cwleave->rules)))
			{
				$is_success 			= new FollowWorkleave;
				$is_success->fill([
						'work_id'		=> $work->id,
					]);

				$is_success->Workleave()->associate($cwleave->workleave);

				if(!$is_success->save())
				{
					$errors->add('Batch', $is_success->getError());
				}
			}
			
			if(!$errors->count())
			{
				if(isset($is_success))
				{
					$morphed 					= new QueueMorph;
					$morphed->fill([
						'queue_id'				=> $id,
						'queue_morph_id'		=> $is_success->id,
						'queue_morph_type'		=> get_class(new FollowWorkleave),
					]);
					$morphed->save();
				}

				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Sukses Menyimpan Kenaikan Hak Cuti '.(isset($value['name']) ? $value['name'] : '');
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}
			else
			{
				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Gagal Menyimpan Kenaikan Hak Cuti '.(isset($value['name']) ? $value['name'] : '');
				$messages['errors'][$pnumber] 	= $errors;

				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}

			$pending->save();

		}

		return true;
	}
}
