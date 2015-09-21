<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\FollowWorkleave;

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
	public function batchraiseworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);
		$messages 					= json_decode($pending->message, true);

		$errors 					= new MessageBag;

		$persons					= Person::organisationid($parameters['id'])->checkwork(true)->currentwork(true)->get();
		
		foreach ($persons as $key => $value) 
		{
			//cek riwayat kerja
			// cek status
			$pwleave 				= FollowWorkleave::workid($value['works'][0]['pivot']['id'])->withattributes(['workleave'])->first();

			$wleave 				= Workleave::workleaveid($pwleave['id'])->where('quota', '>', $pwleave['quota'])->orderBy('quota', 'desc')->first();
			
			$is_success 			= new FollowWorkleave;
			$is_success->fill([
					'work_id'		=> $value['works'][0]['pivot']['id'],
				]);

			$is_success->Workleave()->associate($wleave);

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
					'queue_morph_type'			=> get_class(new FollowWorkleave),
				]);
				$morphed->save();

				$pnumber 						= $pending->total_process;
				$messages['message'][$pnumber] 	= 'Sukses Menyimpan Kenaikan Hak Cuti '.(isset($value['name']) ? $value['name'] : '');
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}
			else
			{
				$pnumber 						= $pending->total_process;
				$messages['message'][$pnumber] 	= 'Gagal Menyimpan Kenaikan Hak Cuti '.(isset($value['name']) ? $value['name'] : '');
				$messages['errors'][$pnumber] 	= $errors;

				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}

			$pending->save();

		}

		return true;
	}
}
