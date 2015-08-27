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

		$asids						= explode(',', $parameters['assp']);
		$ulids						= explode(',', $parameters['ulsp']);
		$hcids						= explode(',', $parameters['hcsp']);
		$htids						= explode(',', $parameters['htsp']);
		$hpids						= explode(',', $parameters['hpsp']);

		$assp1 						= Document::organisationid($parameters['organisation_id'])->id($asids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$assp2 						= Document::organisationid($parameters['organisation_id'])->id($asids[1])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$assp3 						= Document::organisationid($parameters['organisation_id'])->id($asids[2])->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		$ulsp1 						= Document::organisationid($parameters['organisation_id'])->id($ulids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$ulsp2 						= Document::organisationid($parameters['organisation_id'])->id($ulids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$ulsp3 						= Document::organisationid($parameters['organisation_id'])->id($ulids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		$hcsp1 						= Document::organisationid($parameters['organisation_id'])->id($hcids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$hcsp2 						= Document::organisationid($parameters['organisation_id'])->id($hcids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$hcsp3 						= Document::organisationid($parameters['organisation_id'])->id($hcids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		$htsp1 						= Document::organisationid($parameters['organisation_id'])->id($htids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$htsp2 						= Document::organisationid($parameters['organisation_id'])->id($htids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$htsp3 						= Document::organisationid($parameters['organisation_id'])->id($htids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		$hpsp1 						= Document::organisationid($parameters['organisation_id'])->id($hpids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$hpsp2 						= Document::organisationid($parameters['organisation_id'])->id($hpids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();
		
		$hpsp3 						= Document::organisationid($parameters['organisation_id'])->id($hpids[0])->withattributes(['templates'])->orderby('created_at', 'desc')->first();

		if($assp1 && $assp2 && $assp3 && $ulsp1 && $ulsp2 && $ulsp3 && $hcsp1 && $hcsp2 && $hcsp3 && $htsp1 && $htsp2 && $htsp3 && $hpsp1 && $hpsp2 && $hpsp3)
		{

			$persons 				= Person::organisationid($parameters['organisation_id'])->globalsanction(['on' => $parameters['ondate']])->get();

			foreach ($persons as $key => $value) 
			{
				$messages 			= json_decode($pending->message, true);

				//temporary consider as sp
				if($value['HT'] >= $parameters['ht'] || $value['HP'] >= $parameters['hp'] || $value['HC'] >= $parameters['hc'] || $value['AS'] >= $parameters['as'] || $value['UL'] >= $parameters['ul'])
				{
					if($value['HT'] >= $parameters['ht'])
					{
						$probs 					= 'HT';
						$quota 					= $parameters['ht'];
						$docid[0] 				= $htsp1;
						$docid[1]				= $htsp2;
						$docid[2]				= $htsp3;
					}
					elseif($value['HP'] >= $parameters['hp'])
					{
						$probs 					= 'HP';
						$quota 					= $parameters['hp'];
						$docid[0] 				= $hpsp1;
						$docid[1]				= $hpsp2;
						$docid[2]				= $hpsp3;
					}
					elseif($value['HC'] >= $parameters['hc'])
					{
						$probs 					= 'HC';
						$quota 					= $parameters['hc'];
						$docid[0] 				= $hcsp1;
						$docid[1]				= $hcsp2;
						$docid[2]				= $hcsp3;
					}
					elseif($value['UL'] >= $parameters['ul'])
					{
						$probs 					= 'UL';
						$quota 					= $parameters['ul'];
						$docid[0] 				= $ulsp1;
						$docid[1]				= $ulsp2;
						$docid[2]				= $ulsp3;
					}
					else
					{
						$probs 					= 'AS';
						$quota 					= $parameters['as'];
						$docid[0] 				= $assp1;
						$docid[1]				= $assp2;
						$docid[2]				= $assp3;
					}

					DB::beginTransaction();
					
					$errors 					= new MessageBag();

					$pdoc 						= new PersonDocument;
					switch($value['lastdoc'])
					{
						case '0' 	:
								$pdoc->fill(['document_id' => $docid[0]->id]);
								$temp 			= $docid[0]->templates;
							break;
						case '1' 	:
								$pdoc->fill(['document_id' => $docid[1]->id]);
								$temp 			= $docid[1]->templates;
							break;
						default 	:
								$pdoc->fill(['document_id' => $docid[2]->id]);
								$temp 			= $docid[2]->templates;
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

					$pnumber 						= $pending->process_number+1;
					$messages['message'][$pnumber] 	= 'Sukses Menyimpan Surat Peringatan Untuk '.(isset($value['name']) ? $value['name'] : '');
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}
				else
				{
					DB::rollback();
					
					$pnumber 						= $pending->process_number+1;
					$messages['message'][$pnumber] 	= 'Gagal Menyimpan Surat Peringatan Untuk '.(isset($value['name']) ? $value['name'] : '');
					$messages['errors'][$pnumber] 	= $errors;
					
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}

				$pending->save();
			}
		}
	}
}
