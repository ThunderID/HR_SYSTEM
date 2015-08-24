<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Person;
use App\Models\Document;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\AttendanceLog;
use App\Models\AttendanceDetail;

use DB, Hash;

class SanctionCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:sanction';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Second lock for auto settlement. Generate sanction';

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

		$result 		= $this->secondlock($id);
		
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
            array('queueid', null, InputOption::VALUE_OPTIONAL, 'Queue ID', null),
        );
	}

	/**
	 * sanction
	 *
	 * @return void
	 * @author 
	 **/
	public function secondlock($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);

		$errors 					= new MessageBag;

		$sp1 						= Document::organisationid($parameters['organisation_id'])->name('Surat Peringatan I')->tag('SP')->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$sp2 						= Document::organisationid($parameters['organisation_id'])->name('Surat Peringatan II')->tag('SP')->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$sp3 						= Document::organisationid($parameters['organisation_id'])->name('Surat Peringatan III')->tag('SP')->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		if($sp1 && $sp2 && $sp3)
		{

			$persons 				= Person::organisationid($parameters['organisation_id'])->globalsanction(['on' => $parameters['ondate']])->get();

			foreach ($persons as $key => $value) 
			{
				//temporary consider as sp
				if($value['HT'] >= $parameters['ht'] || $value['HP'] >= $parameters['hp'] || $value['HC'] >= $parameters['hc'] || $value['AS'] >= $parameters['as'] || $value['UL'] >= $parameters['ul'])
				{
					if($value['HT'] >= $parameters['ht'])
					{
						$probs 					= 'HT';
						$quota 					= $parameters['ht'];
					}
					elseif($value['HP'] >= $parameters['hp'])
					{
						$probs 					= 'HP';
						$quota 					= $parameters['hp'];
					}
					elseif($value['HC'] >= $parameters['hc'])
					{
						$probs 					= 'HC';
						$quota 					= $parameters['hc'];
					}
					elseif($value['UL'] >= $parameters['ul'])
					{
						$probs 					= 'UL';
						$quota 					= $parameters['ul'];
					}
					else
					{
						$probs 					= 'AS';
						$quota 					= $parameters['as'];
					}

					DB::beginTransaction();
					
					$errors 					= new MessageBag();

					$pdoc 						= new PersonDocument;
					switch($value['lastdoc'])
					{
						case '0' 	:
								$pdoc->fill(['document_id' => $sp1->id]);
								$temp 			= $sp1->templates;
							break;
						case '1' 	:
								$pdoc->fill(['document_id' => $sp2->id]);
								$temp 			= $sp2->templates;
							break;
						default 	:
								$pdoc->fill(['document_id' => $sp3->id]);
								$temp 			= $sp3->templates;
							break;
					}

					$pdoc->Person()->associate(Person::find($value['id']));
					if(!$pdoc->save())
					{
						$errors->add('Person', json_encode($pdoc->getError()));
					}

					foreach ($temp as $key2 => $value) 
					{
						$pdocdet 				= new DocumentDetail;
						switch($key2)
						{
							case '0' 	:
								$pdocdet->fill(['template_id' => $value->id, 'string' => date('Y/M/D/H/I/S').'-'.$value['code']]);
								break;
							case '1' 	:
								$pdocdet->fill(['template_id' => $value->id, 'on' => date('Y-m-d')]);
								break;
							default 	:
								$pdocdet->fill(['template_id' => $value->id, 'text' => 'Surat Peringatan '.($value['lastdoc'] + 1).' Status '.$probs.'. Powered By HRIS.']);
								break;
						}
					
						$pdocdet->PersonDocument()->associate($pdoc);

						if(!$pdocdet->save())
						{
							$errors->add('Person', json_encode($pdocdet->getError()));
						}
					}

					$adetails 					= AttendanceLog::globalsanction(['on' => $parameters['ondate'], 'personid' => $value['id']])->get();
					foreach ($adetails as $key3 => $value3) 
					{
						if($key3<$quota)
						{
							$adetail 			= new AttendanceDetail;
							$adetail->fill(['attendance_log_id' => $value3->id]);

							$adetail->PersonDocument()->associate($pdoc);
							if(!$adetail->save())
							{
								$errors->add('Person', json_encode($adetail->getError()));
							}
						}
					}

					if(!$errors->count())
					{
						$morphed 						= new QueueMorph;

						$morphed->fill([
							'queue_id'					=> $id,
							'queue_morph_id'			=> $pdoc->id,
							'queue_morph_type'			=> get_class(new PersonDocument),
						]);

						$morphed->save();
					}
				}

				if(!$errors->count())
				{
					DB::commit();

					$pnumber 							= $pending->process_number+1;

					$pending->fill(['process_number' => $pnumber, 'message' => 'Sedang Menyimpan First Lock Settlement '.(isset($parameters['name']) ? $parameters['name'] : '')]);
				}
				else
				{
					DB::rollback();
					
					$pending->fill(['message' => json_encode($errors)]);
				}

				$pending->save();
			}

			if($errors->count())
			{
				$pending->fill(['message' => json_encode($errors)]);
			}
			else
			{
				$pending->fill(['process_number' => $pending->total_process, 'message' => 'Sukses Menyimpan First Lock Settlement '.(isset($parameters['name']) ? $parameters['name'] : '')]);
			}

			$pending->save();
		}
	}
}
