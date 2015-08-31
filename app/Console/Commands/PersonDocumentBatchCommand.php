<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Person;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;

use DB, Hash, App;

class PersonDocumentBatchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:persondocumentbatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Persons` Documents.';

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
		
		$result 		= $this->batchcompletedocument($id);

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
	public function batchcompletedocument($id)
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
				}

				//save doc
				if(!$errors->count())
				{
					$document 								= Document::organisationid($organisation['id'])->id($parameters['doc_id'])->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen doc');
					}
					else
					{
						$doc[$i]['document_id']				= $document['id'];

						$is_doc_success 					= new PersonDocument;
						$is_doc_success->fill($doc[$i]);
						$is_doc_success->Person()->associate($is_success);

						if(!$is_doc_success->save())
						{
							$errors->add('Batch', $is_doc_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_doc_success->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}

					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							$docu 							= strtolower(str_replace(' ', '', $value['field']));
							foreach ($row as $key2 => $value2) 
							{
								if($docu==$key2)
								{
									$docdetail[$i][$key]['template_id']			= $value['id'];
									if($value['type']=='date')
									{
										$docdetail[$i][$key]['on']				= $value2;
									}
									else
									{
										$docdetail[$i][$key][$value['type']]	= $value2;
									}
								}
							}

							if(isset($docdetail[$i][$key]) && !$errors->count())
							{
								$is_docd_success 			= new DocumentDetail;
								$is_docd_success->fill($docdetail[$i]);
								$is_docd_success->PersonDocument()->associate($is_doc_success);

								if(!$is_docd_success->save())
								{
									$errors->add('Batch', $is_docd_success->getError());
								}
								else
								{
									$morphed 				= new QueueMorph;

									$morphed->fill([
										'queue_id'			=> $pending->id,
										'queue_morph_id'	=> $is_docd_success->id,
										'queue_morph_type'	=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}
			
				if(!$errors->count())
				{
					DB::commit();

					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber]		 	= 'Sukses Menyimpan Dokumen Karyawan '.(isset($person['name']) ? $parameters['name'] : '');
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}
				else
				{
					DB::rollback();
					
					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber] 			= 'Gagal Menyimpan Dokumen Karyawan '.(isset($person['name']) ? $parameters['name'] : '');
					$messages['errors'][$pnumber] 			= $errors;

					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}

				$pending->save();
			}
		}

		return true;
	}
}
