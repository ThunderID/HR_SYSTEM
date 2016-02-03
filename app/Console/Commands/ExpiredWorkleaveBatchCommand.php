<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\PersonWorkleave;
use App\Models\Person;
use App\Models\Work;
use \Illuminate\Support\MessageBag as MessageBag;

class ExpiredWorkleaveBatchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:expireworkleavebatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Expire queue running.';

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

		$result 		= $this->batchexpiredworkleave($id);

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
	public function batchexpiredworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);
		$messages 					= json_decode($pending->message, true);

		$errors 					= new MessageBag;

		$persons					= Person::organisationid($parameters['organisation_id'])->checkwork(true)->get();
		
		foreach ($persons as $key => $value) 
		{
			$pwleaveplus 			= PersonWorkleave::personid($value['id'])->Quota(true)->where('end', '<=', date('Y-m-d', strtotime($parameters['end'])))->sum('quota');
			$pwleaveminus 			= PersonWorkleave::personid($value['id'])->Quota(false)->where('end', '<=', date('Y-m-d', strtotime($parameters['end'])))->sum('quota');
			
			$pwleave 				= $pwleaveplus - $pwleaveminus;

			$work 					= Work::active(true)->personid($value['id'])->first();
			
			$pwleaves 				= PersonWorkleave::personid($value['id'])->Quota(true)->where('end', '<=', date('Y-m-d', strtotime($parameters['end'])))->first();

			if($pwleave > 0 && $work && $pwleaves)
			{
				$is_success 					= new PersonWorkleave;
				$is_success->fill([
						'work_id'				=> $work->id,
						'person_workleave_id'	=> $pwleaves->id,
						'name'					=> 'Expired Workleave',
						'notes'					=> 'Auto generated expire workleave',
						'start'					=> date('Y-m-d', strtotime($parameters['end'])),
						'end'					=> date('Y-m-d', strtotime($parameters['end'])),
						'quota'					=> 0 - abs($pwleave),
						'status'				=> 'CN',
					]);

				$is_success->Person()->associate($value);

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
						'queue_morph_type'		=> get_class(new PersonWorkleave),
					]);
					$morphed->save();
				}

				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Sukses Menyimpan Cuti '.(isset($value['name']) ? $value['name'] : '');
				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}
			else
			{
				$pnumber 						= $pending->process_number+1;
				$messages['message'][$pnumber] 	= 'Gagal Menyimpan Cuti '.(isset($value['name']) ? $value['name'] : '');
				$messages['errors'][$pnumber] 	= $errors;

				$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
			}

			$pending->save();

		}

		return true;
	}
}
