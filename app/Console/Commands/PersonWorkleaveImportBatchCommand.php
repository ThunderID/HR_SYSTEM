<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Person;
use App\Models\PersonWorkleave;

use DB, Hash, App;

class PersonWorkleaveImportBatchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:personworkleaveimportbatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Persons` Workleaves.';

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
		
		$result 		= $this->batchcompleteworkleave($id);

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
	 * special to create person
	 *
	 * @return void
	 * @author 
	 **/
	public function batchcompleteworkleave($id)
	{
		$queue 									= new Queue;
		$pending 								= $queue->find($id);

		$parameters 							= json_decode($pending->parameter, true);

		DB::beginTransaction();

		$errors 								= new MessageBag();

		foreach($parameters['csv'] as $i => $row)
		{
			if($i<=$pending->process_number)
			{
				$person 							= Person::uniqid($row['nik'])->checkwork(true)->currentwork(true)->first();

				if($person && isset($row['sisacuti']))
				{
					$work 							= $person['work'][0];
					$person_workleave 				= PersonWorkleave::personid($person->id)->quota(true)->OnDate('now')->first();

					if($person_workleave)
					{
						$pwleave[$i]['person_workleave_id'] = $person_workleave->id;
					}

					$sum 							= PersonWorkleave::personid($person->id)->quota(true)->OnDate('now')->sum('quota');

					$pwleave[$i]['work_id'] 		= $work->id;
					$pwleave[$i]['created_by'] 		= $pending->created_by;
					$pwleave[$i]['name'] 			= 'Pengambilan (awal) '.$is_workleave_success->name;
					$pwleave[$i]['notes'] 			= 'Auto generated dari import csv. Hanya Quota yang valid';
					
					if($sum)
					{
						$leave 						= $sum - $row['sisacuti'];
					}
					else
					{
						$leave 						= 0 - $row['sisacuti'];
					}

					if($leave == 1)
					{
						$leaves 					= '';
					}
					elseif($leave==2)
					{
						$leaves 					= ' + 1 day';
					}
					else
					{
						$leaves 					= ' + '.$leave.' days';
					}

					$pwleave[$i]['start'] 				= date('Y-m-d', strtotime($work->start));
					$pwleave[$i]['end'] 				= date('Y-m-d', strtotime($work->start.$leaves));
					$pwleave[$i]['quota'] 				= 0 - $leave;
					$pwleave[$i]['status'] 				= 'CN';

					$is_pwleave_success 				= new PersonWorkleave;
					$is_pwleave_success->fill($pwleave[$i]);
					$is_pwleave_success->Person()->associate($person);

					if(!$is_pwleave_success->save())
					{
						$errors->add('Batch', $is_pwleave_success->getError());
					}
					else
					{
						$morphed 						= new QueueMorph;

						$morphed->fill([
							'queue_id'					=> $pending->id,
							'queue_morph_id'			=> $is_pwleave_success->id,
							'queue_morph_type'			=> get_class(new PersonWorkleave),
						]);

						$morphed->save();
					}
				}

				if(!$errors->count())
				{
					DB::commit();

					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber]		 	= 'Sukses Menyimpan Data Karyawan '.(isset($person['name']) ? $person['name'] : '');
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}
				else
				{
					DB::rollback();
					
					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber] 			= 'Gagal Menyimpan Data Karyawan '.(isset($row['nik']) ? $row['nik'] : '');
					$messages['errors'][$pnumber] 			= $errors;

					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}

				$pending->save();
			}
		}

		return true;
	}
}
