<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WorkBatchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:workbatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Persons` Works.';

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
		
		$result 		= $this->batchcompletework($id);

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
	public function batchcompletework($id)
	{
		$queue 									= new Queue;
		$pending 								= $queue->find($id);

		$parameters 							= json_decode($pending->parameter, true);

		foreach($parameters['csv'] as $i => $row)
		{
			if(($pending->process_number-1) < $i)
			{
				$messages 						= json_decode($pending->message, true);
				
				DB::beginTransaction();

				$errors 						= new MessageBag();

				if(!$errors->count())
				{
					$person 					= Person::uniqid($row['nik'])->withattributes(['organisation'])->first();

					if(!$person)
					{
						$errors->add('Batch', 'Karyawan tidak terdaftar');
					}

					$organisation 				= $person['organisation'];

					$is_chart_success 			= Chart::organisationid($organisation['id'])->branchname($row['cabang'])->tag($row['namadepartemen'])->name($row['jabatan'])->first();

					$is_calendar_success 		= Calendar::organisationid($organisation['id'])->first();
					
					if(!$is_chart_success)
					{
						$errors->add('Batch', 'Jabatan tidak terdaftar');
					}

					if(!$is_calendar_success)
					{
						$errors->add('Batch', 'Kalender kerja tidak terdaftar');
					}
				}

				//save work
				if(!$errors->count())
				{
					switch (strtolower($row['statuskerja'])) 
					{
						case 'kontrak': case 'contract' :
							$search['status'] 				= 'contract';
							break;						
						case 'tetap': case 'permanent' : case 'permanen' :
							$search['status'] 				= 'permanent';
							break;
						case 'percobaan': case 'probation' :
							$search['status'] 				= 'probation';
							break;
						case 'magang': case 'internship' : case 'training' :
							$search['status'] 				= 'training';
							break;
						default:
							$search['status'] 				= 'others';
							break;
					}

					$is_work_success 						= Work::chartid($is_chart_success->id)->calendarid($is_calendar_success->id)->personid($person->id)->status($search['status'])->first();

					if(!$is_work_success)
					{
						$work[$i]['chart_id'] 				= $is_chart_success->id;
						$work[$i]['calendar_id'] 			= $is_calendar_success->id;
						$work[$i]['status'] 				= $search['status'];
						$work[$i]['grade'] 					= $row['grade'];
						
						list($d, $m, $y) 					= explode("/", $row['mulaikerja']);
						$work[$i]['start']					= date('Y-m-d', strtotime("$y-$m-$d"));
						
						if($row['berhentikerja']!='')
						{
							list($d, $m, $y) 				= explode("/", $row['berhentikerja']);
							$work[$i]['end']				= date('Y-m-d', strtotime("$y-$m-$d"));

							$work[$i]['reason_end_job'] 	= 'Akhir '.$row['statuskerja'];
						}

						$is_work_success 					= new Work;
						$is_work_success->fill($work);
						$is_work_success->Person()->associate($is_success);

						if(!$is_work_success->save())
						{
							$errors->add('Batch', $is_work_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_work_success->id,
								'queue_morph_type'			=> get_class(new Work),
							]);

							$morphed->save();
						}
					}
				}
			
				if(!$errors->count())
				{
					DB::commit();

					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber]		 	= 'Sukses Menyimpan Pekerjaan Karyawan '.(isset($person['name']) ? $parameters['name'] : '');
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}
				else
				{
					DB::rollback();
					
					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber] 			= 'Gagal Menyimpan Pekerjaan Karyawan '.(isset($person['name']) ? $parameters['name'] : '');
					$messages['errors'][$pnumber] 			= $errors;

					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}

				$pending->save();
			}
		}

		return true;
	}
}
