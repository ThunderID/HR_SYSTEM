<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

use App\Console\Commands\Saving;
use App\Console\Commands\Getting;

use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Organisation;
use App\Models\Person;
use App\Models\Work;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\Contact;
use App\Models\Branch;
use App\Models\MaritalStatus;
use App\Models\Document;
use App\Models\Calendar;
use App\Models\Chart;
use App\Models\Follow;
use App\Models\Workleave;
use App\Models\FollowWorkleave;
use App\Models\PersonWorkleave;
use App\Models\PersonSchedule;

use DB, Hash, App;

class PersonBatchCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:personbatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Person Command.';

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
		$id 			= $this->argument()['queueid'];
		
		$result 		= $this->batchcompleteperson($id);

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
	public function batchcompleteperson($id)
	{
		$queue 									= new Queue;
		$pending 								= $queue->find($id);

		$parameters 							= json_decode($pending->parameter, true);


		foreach($parameters as $i => $row)
		{
			if(($pending->process_number-1) < $i)
			{
				DB::beginTransaction();

				$errors 						= new MessageBag();

				if(!isset($prev_org_code) || $prev_org_code!=$row['kodeorganisasi'])
				{
					unset($search);
					unset($sort);

					$search['code']						= $row['kodeorganisasi'];
					$sort 								= ['created_at' => 'asc'];
					$results 							= $this->dispatch(new Getting(new Organisation, $search, $sort , 1, 1));
					$contents 							= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$organisation 					= json_decode(json_encode($contents->data), true);
						$org_id_code 					= $organisation['id'];
						$prev_org_code 					= $row['kodeorganisasi'];
					}
				}

				unset($search);
				unset($sort);

				if(!$errors->count())
				{
					$search['uniqid']						= $row['nik'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
					$contents 								= json_decode($results);

					if(!$contents->meta->success)
					{
						$attributes[$i]['uniqid']			= $row['nik'];
						$attributes[$i]['prefix_title']		= (!is_null($row['prefixtitle']) ? $row['prefixtitle'] : '');
						$attributes[$i]['name']				= $row['namalengkap'];
						$attributes[$i]['suffix_title']		= (!is_null($row['suffixtitle']) ? $row['suffixtitle'] : '');
						
						$originaluname						= explode(' ', strtolower($attributes[$i]['name']));
						$modifyuname						= $originaluname[0];
						$idxuname 							= 0;

						do
						{
							$idxuname++;

							unset($search);
							unset($sort);

							$search['username']						= $modifyuname.'.'.$row['kodeorganisasi'];
							$sort 									= ['created_at' => 'asc'];
							$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 1));
							$contents 								= json_decode($results);

							if(isset($originaluname[$idxuname]))
							{
								$modifyuname 						= $modifyuname.$originaluname[$idxuname][0];
							}
							else
							{
								$modifyuname 						= $modifyuname.$modifyuname;
							}
						}
						while($contents->meta->success);

						$attributes[$i]['username']			= $modifyuname.'.'.$row['kodeorganisasi'];

						$attributes[$i]['password']			= Hash::make($row['nik']);
						$attributes[$i]['place_of_birth']	= $row['tempatlahir'];
						$attributes[$i]['last_password_updated_at'] = date('Y-m-d H:i:s', strtotime('- 3 month'));

						list($d, $m, $y) 					= explode("/", $row['tanggallahir']);
						$attributes[$i]['date_of_birth']	= date('Y-m-d', strtotime("$y-$m-$d"));

						$attributes[$i]['gender']			= $row['gender']=='L' ? 'male' : 'female';
						$attributes[$i]['org_id']			= $org_id_code;

						$content 							= $this->dispatch(new Saving(new Person, $attributes[$i], null, new Organisation, $org_id_code));

						$is_success 						= json_decode($content);
						if(!$is_success->meta->success)
						{
							foreach ($is_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							
							$is_success 					= $is_success->data;
							
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_success->id,
								'queue_morph_type'			=> get_class(new Person),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_success 						= $contents->data;
					}
				}

				//save contact
				if(!$errors->count())
				{
					if(!is_null($row['email']))
					{
						$email[$i]['item']				= 'email';
						$email[$i]['value']				= $row['email'];
						$email[$i]['is_default']		= true;

						$content 						= $this->dispatch(new Saving(new Contact, $email[$i], null, new Person, $is_success->id));

						$is_mail_success 				= json_decode($content);
						if(!$is_mail_success->meta->success)
						{
							foreach ($is_mail_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_mail_success->data->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}

					if(!is_null($row['phone']))
					{
						$phone[$i]['item']				= 'phone';
						$phone[$i]['value']				= $row['phone'];
						$phone[$i]['is_default']		= true;

						$content 						= $this->dispatch(new Saving(new Contact, $phone[$i], null, new Person, $is_success->id));

						$is_phone_success 				= json_decode($content);
						if(!$is_phone_success->meta->success)
						{
							foreach ($is_phone_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_phone_success->data->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}


					if(!is_null($row['alamat']))
					{
						$address[$i]['item']			= 'address';
						$address[$i]['value']			= $row['alamat'];
						$address[$i]['is_default']		= true;

						$content 						= $this->dispatch(new Saving(new Contact, $address[$i], null, new Person, $is_success->id));

						$is_address_success 				= json_decode($content);
						if(!$is_address_success->meta->success)
						{
							foreach ($is_address_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_address_success->data->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}
				}

				//save marital statuses
				if(!$errors->count())
				{
					$marital[$i]['status']			= $row['statuskawin'];
					$marital[$i]['on']				= date('Y-m-d H:i:s');

					$content 						= $this->dispatch(new Saving(new MaritalStatus, $marital[$i], null, new Person, $is_success->id));

					$is_marital_success 				= json_decode($content);
					if(!$is_marital_success->meta->success)
					{
						foreach ($is_marital_success->meta->errors as $key => $value) 
						{
							if(is_array($value))
							{
								foreach ($value as $key2 => $value2) 
								{
									$errors->add('Person', $value2);
								}
							}
							else
							{
								$errors->add('Person', $value);
							}
						}
					}
					else
					{
						$morphed 						= new QueueMorph;

						$morphed->fill([
							'queue_id'					=> $pending->id,
							'queue_morph_id'			=> $is_marital_success->data->id,
							'queue_morph_type'			=> get_class(new MaritalStatus),
						]);

						$morphed->save();
					}
				}

				//save ktp
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= 'KTP';
					$search['tag']							= 'Identitas';
					$search['withattributes']				= ['templates'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$document 								= json_decode(json_encode($contents->data), true);

						$ktp[$i]['document_id']					= $document['id'];

						$content 								= $this->dispatch(new Saving(new PersonDocument, $ktp[$i], null, new Person, $is_success->id));
						$is_ktp_success 						= json_decode($content);
						
						if(!$is_ktp_success->meta->success)
						{
							foreach ($is_ktp_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_ktp_success->data->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}



					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							switch($key)
							{
								case '0': 
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['string']		= $row['nomorktp'];
									break;
								case '1':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['text']		= $row['alamat'];
									break;
								case '2':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['string']		= $row['kota'];
									break;
								case '3':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['string']		= $row['agama'];
									break;
								case '4':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['string']		= $row['statuskawin'];
									break;
								case '5':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['string']		= $row['kewarganegaraan'];
									break;
								case '6':
									$ktpdetail[$i][$key]['template_id']	= $value['id'];
									$ktpdetail[$i][$key]['on']			= $row['berlakuhingga'];
									break;
							}

							if(isset($ktpdetail[$i][$key]) && !$errors->count())
							{
								$content 								= $this->dispatch(new Saving(new DocumentDetail, $ktpdetail[$i][$key], null, new PersonDocument, $is_ktp_success->data->id));
								$is_ktpd_success 						= json_decode($content);
								
								if(!$is_ktpd_success->meta->success)
								{
									foreach ($is_ktpd_success->meta->errors as $key => $value) 
									{
										if(is_array($value))
										{
											foreach ($value as $key2 => $value2) 
											{
												$errors->add('Person', $value2);
											}
										}
										else
										{
											$errors->add('Person', $value);
										}
									}
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_ktpd_success->data->id,
										'queue_morph_type'			=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}

				//save bank account
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= 'Akun Bank';
					$search['tag']							= 'Akun';
					$search['withattributes']				= ['templates'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$document 								= json_decode(json_encode($contents->data), true);

						$bank[$i]['document_id']				= $document['id'];

						$content 								= $this->dispatch(new Saving(new PersonDocument, $bank[$i], null, new Person, $is_success->id));
						$is_bank_success 						= json_decode($content);
						
						if(!$is_bank_success->meta->success)
						{
							foreach ($is_bank_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bank_success->data->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}

					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							switch($key)
							{
								case '0': 
									$bankdetail[$i][$key]['template_id']	= $value['id'];
									$bankdetail[$i][$key]['string']			= $row['nomorrekening'];
									break;
								case '1':
									$bankdetail[$i][$key]['template_id']	= $value['id'];
									$bankdetail[$i][$key]['string']			= $row['namabank'];
									break;
								case '2':
									$bankdetail[$i][$key]['template_id']	= $value['id'];
									$bankdetail[$i][$key]['string']			= $row['namanasabah'];
									break;
							}

							if(isset($bankdetail[$i][$key]) && !$errors->count())
							{
								$content 								= $this->dispatch(new Saving(new DocumentDetail, $bankdetail[$i][$key], null, new PersonDocument, $is_bank_success->data->id));
								$is_bankd_success 						= json_decode($content);
								
								if(!$is_bankd_success->meta->success)
								{
									foreach ($is_bankd_success->meta->errors as $key => $value) 
									{
										if(is_array($value))
										{
											foreach ($value as $key2 => $value2) 
											{
												$errors->add('Person', $value2);
											}
										}
										else
										{
											$errors->add('Person', $value);
										}
									}
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bankd_success->data->id,
										'queue_morph_type'			=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}

				//save npwp
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= 'NPWP';
					$search['tag']							= 'Pajak';
					$search['withattributes']				= ['templates'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$document 								= json_decode(json_encode($contents->data), true);

						$npwp[$i]['document_id']				= $document['id'];

						$content 								= $this->dispatch(new Saving(new PersonDocument, $npwp[$i], null, new Person, $is_success->id));
						$is_npwp_success 						= json_decode($content);
						
						if(!$is_npwp_success->meta->success)
						{
							foreach ($is_npwp_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_npwp_success->data->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}

					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							switch($key)
							{
								case '0': 
									$npwpdetail[$i][$key]['template_id']	= $value['id'];
									$npwpdetail[$i][$key]['string']			= $row['npwp'];
									break;
							}

							if(isset($npwpdetail[$i][$key]) && !$errors->count())
							{
								$content 								= $this->dispatch(new Saving(new DocumentDetail, $npwpdetail[$i][$key], null, new PersonDocument, $is_npwp_success->data->id));
								$is_npwpd_success 						= json_decode($content);
								
								if(!$is_npwpd_success->meta->success)
								{
									foreach ($is_npwpd_success->meta->errors as $key => $value) 
									{
										if(is_array($value))
										{
											foreach ($value as $key2 => $value2) 
											{
												$errors->add('Person', $value2);
											}
										}
										else
										{
											$errors->add('Person', $value);
										}
									}
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_npwpd_success->data->id,
										'queue_morph_type'			=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}

				//save bpjstk
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= 'BPJS Ketenagakerjaan';
					$search['tag']							= 'Pajak';
					$search['withattributes']				= ['templates'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$document 								= json_decode(json_encode($contents->data), true);

						$bpjstk[$i]['document_id']				= $document['id'];

						$content 								= $this->dispatch(new Saving(new PersonDocument, $bpjstk[$i], null, new Person, $is_success->id));
						$is_bpjstk_success 						= json_decode($content);
						
						if(!$is_bpjstk_success->meta->success)
						{
							foreach ($is_bpjstk_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bpjstk_success->data->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}

					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							switch($key)
							{
								case '0': 
									$bpjstkdetail[$i][$key]['template_id']	= $value['id'];
									$bpjstkdetail[$i][$key]['string']		= $row['bpjsketenagakerjaan'];
									break;
							}

							if(isset($bpjstkdetail[$i][$key]) && !$errors->count())
							{
								$content 								= $this->dispatch(new Saving(new DocumentDetail, $bpjstkdetail[$i][$key], null, new PersonDocument, $is_bpjstk_success->data->id));
								$is_bpjstkd_success 						= json_decode($content);
								
								if(!$is_bpjstkd_success->meta->success)
								{
									foreach ($is_bpjstkd_success->meta->errors as $key => $value) 
									{
										if(is_array($value))
										{
											foreach ($value as $key2 => $value2) 
											{
												$errors->add('Person', $value2);
											}
										}
										else
										{
											$errors->add('Person', $value);
										}
									}
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bpjstkd_success->data->id,
										'queue_morph_type'			=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}

				//save bpjs kesehatan
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= 'BPJS Kesehatan';
					$search['tag']							= 'Pajak';
					$search['withattributes']				= ['templates'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Document, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$errors->add('Batch', json_encode($contents->meta->errors));
					}
					else
					{
						$document 								= json_decode(json_encode($contents->data), true);

						$bpjsk[$i]['document_id']				= $document['id'];

						$content 								= $this->dispatch(new Saving(new PersonDocument, $bpjsk[$i], null, new Person, $is_success->id));
						$is_bpjsk_success 						= json_decode($content);
						
						if(!$is_bpjsk_success->meta->success)
						{
							foreach ($is_bpjsk_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bpjsk_success->data->id,
								'queue_morph_type'			=> get_class(new PersonDocument),
							]);

							$morphed->save();
						}
					}

					if(!$errors->count())
					{
						foreach ($document['templates'] as $key => $value) 
						{
							switch($key)
							{
								case '0': 
									$bpjskdetail[$i][$key]['template_id']	= $value['id'];
									$bpjskdetail[$i][$key]['string']		= $row['bpjskesehatan'];
									break;
							}

							if(isset($bpjskdetail[$i][$key]) && !$errors->count())
							{
								$content 								= $this->dispatch(new Saving(new DocumentDetail, $bpjskdetail[$i][$key], null, new PersonDocument, $is_bpjsk_success->data->id));
								$is_bpjskd_success 						= json_decode($content);
								
								if(!$is_bpjskd_success->meta->success)
								{
									foreach ($is_bpjskd_success->meta->errors as $key => $value) 
									{
										if(is_array($value))
										{
											foreach ($value as $key2 => $value2) 
											{
												$errors->add('Person', $value2);
											}
										}
										else
										{
											$errors->add('Person', $value);
										}
									}
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bpjskd_success->data->id,
										'queue_morph_type'			=> get_class(new DocumentDetail),
									]);

									$morphed->save();
								}
							}
						}
					}
				}

				//save kalender
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['starthour']					= date('H:i:s', strtotime($row['jammasukkerja']));
					$search['endhour']						= date('H:i:s', strtotime($row['jampulangkerja']));
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Calendar, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$calendar[$i]['import_from_id'] 	= 1;
						$calendar[$i]['name'] 				= 'Costum Kalender Untuk '.$row['namacabang'];
						$calendar[$i]['workdays'] 			= 'senin,selasa,rabu,kamis,jumat';
						$calendar[$i]['start'] 				= date('H:i:s', strtotime($row['jammasukkerja']));
						$calendar[$i]['end'] 				= date('H:i:s', strtotime($row['jampulangkerja']));

						$content 							= $this->dispatch(new Saving(new Calendar, $calendar[$i], null, new Organisation, $org_id_code));

						$is_calendar_success 				= json_decode($content);
						if(!$is_calendar_success->meta->success)
						{
							foreach ($is_calendar_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$is_calendar_success 			= $is_calendar_success->data;

							$morphed 							= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_calendar_success->id,
								'queue_morph_type'			=> get_class(new Calendar),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_calendar_success 				= $contents->data;
					}
				}

				//save branch
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['name']							= $row['namacabang'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Branch, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$branch[$i]['name'] 				= $row['namacabang'];

						$content 							= $this->dispatch(new Saving(new Branch, $branch[$i], null, new Organisation, $org_id_code));

						$is_branch_success 					= json_decode($content);
						if(!$is_branch_success->meta->success)
						{
							foreach ($is_branch_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$is_branch_success 				= $is_branch_success->data;

							$morphed 							= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_branch_success->id,
								'queue_morph_type'			=> get_class(new Branch),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_branch_success 					= $contents->data;
					}
				}

				//save Chart
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['organisationid']				= $org_id_code;
					$search['branchid']						= $is_branch_success->id;
					$search['tag']							= $row['namadepartemen'];
					$search['name']							= $row['jabatan'];
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Chart, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$chart[$i]['name'] 					= $row['jabatan'];
						$chart[$i]['tag'] 					= $row['namadepartemen'];
						$chart[$i]['min_employee'] 			= 10;
						$chart[$i]['ideal_employee'] 		= 10;
						$chart[$i]['max_employee'] 			= 10;

						$content 							= $this->dispatch(new Saving(new Chart, $chart[$i], null, new Branch, $is_branch_success->id));

						$is_chart_success 					= json_decode($content);
						if(!$is_chart_success->meta->success)
						{
							foreach ($is_chart_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$is_chart_success 				= $is_chart_success->data;

							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_chart_success->id,
								'queue_morph_type'			=> get_class(new Chart),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_chart_success 					= $contents->data;
					}
				}

				//save Follow
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['chartid']						= $is_chart_success->id;
					$search['calendarid']					= $is_calendar_success->id;
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Follow, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$follow[$i]['chart_id'] 			= $is_chart_success->id;

						$content 							= $this->dispatch(new Saving(new Follow, $follow[$i], null, new Calendar, $is_calendar_success->id));

						$is_follow_success 					= json_decode($content);
						if(!$is_follow_success->meta->success)
						{
							foreach ($is_follow_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_follow_success->data->id,
								'queue_morph_type'			=> get_class(new Follow),
							]);

							$morphed->save();
						}

					}
				}

				//Check Cuti 
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['quota']						= $row['kuotacutitahunan'];
					$search['status']						= 'CN';
					$search['active']						= true;
					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$workleave[$i]['name'] 				= 'Custom Cuti '.$row['namacabang'];
						$workleave[$i]['quota'] 			= $row['kuotacutitahunan'];
						$workleave[$i]['status'] 			= 'CN';
						$workleave[$i]['is_active'] 		= true;

						$content 							= $this->dispatch(new Saving(new Workleave, $workleave[$i], null, new Organisation, $org_id_code));

						$is_workleave_success 				= json_decode($content);
						if(!$is_workleave_success->meta->success)
						{
							foreach ($is_workleave_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$is_workleave_success 			= $is_workleave_success->data;

							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_workleave_success->id,
								'queue_morph_type'			=> get_class(new Workleave),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_workleave_success 				= $contents->data;
					}
				}

				//save Work
				if(!$errors->count())
				{
					unset($search);
					unset($sort);

					$search['chartid']						= $is_chart_success->id;
					$search['calendarid']					= $is_calendar_success->id;
					$search['personid']						= $is_success->id;
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

					$sort 									= ['created_at' => 'asc'];
					$results 								= $this->dispatch(new Getting(new Work, $search, $sort , 1, 1));
					$contents 								= json_decode($results);		

					if(!$contents->meta->success)
					{
						$work[$i]['chart_id'] 				= $is_chart_success->id;
						$work[$i]['calendar_id'] 			= $is_calendar_success->id;
						$work[$i]['status'] 				= $search['status'];
						
						list($d, $m, $y) 					= explode("/", $row['mulaikerja']);
						$work[$i]['start']					= date('Y-m-d', strtotime("$y-$m-$d"));
						
						if($row['berhentikerja']!='')
						{
							list($d, $m, $y) 				= explode("/", $row['berhentikerja']);
							$work[$i]['end']				= date('Y-m-d', strtotime("$y-$m-$d"));

							$work[$i]['reason_end_job'] 	= 'Akhir '.$row['statuskerja'];
						}

						$content 							= $this->dispatch(new Saving(new Work, $work[$i], null, new Person, $is_success->id));

						$is_work_success 					= json_decode($content);
						if(!$is_work_success->meta->success)
						{
							foreach ($is_work_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$is_work_success 				= $is_work_success->data;

							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_work_success->id,
								'queue_morph_type'			=> get_class(new Work),
							]);

							$morphed->save();
						}
					}
					else
					{
						$is_work_success 					= $contents->data;
					}
				}

				//save FolloWorkleve
				if(!$errors->count())
				{
					$fwleave[$i]['work_id'] 		= $is_work_success->id;

					$content 						= $this->dispatch(new Saving(new FollowWorkleave, $fwleave[$i], null, new Workleave, $is_workleave_success->id));

					$is_fwleave_success 			= json_decode($content);
					if(!$is_fwleave_success->meta->success)
					{
						foreach ($is_fwleave_success->meta->errors as $key => $value) 
						{
							if(is_array($value))
							{
								foreach ($value as $key2 => $value2) 
								{
									$errors->add('Person', $value2);
								}
							}
							else
							{
								$errors->add('Person', $value);
							}
						}
					}
					else
					{
						$morphed 						= new QueueMorph;

						$morphed->fill([
							'queue_id'					=> $pending->id,
							'queue_morph_id'			=> $is_fwleave_success->data->id,
							'queue_morph_type'			=> get_class(new FollowWorkleave),
						]);

						$morphed->save();
					}
				}

				//save Pemberian Cuti
				if(!$errors->count())
				{
					if(in_array($is_work_success->status, ['contract', 'permanent']))
					{
						$personworkleave[$i]['work_id'] 		= $is_work_success->id;
						$personworkleave[$i]['workleave_id'] 	= $is_workleave_success->id;
						$personworkleave[$i]['created_by'] 		= $pending->created_by;
						$personworkleave[$i]['name'] 			= 'Pemberian (awal) '.$is_workleave_success->name;
						$personworkleave[$i]['notes'] 			= 'Auto generated dari import csv.';
						$personworkleave[$i]['start'] 			= date('Y-m-d', strtotime('first day of January this year'));
						$personworkleave[$i]['end'] 			= date('Y-m-d', strtotime('last day of December this year'));
						$personworkleave[$i]['quota'] 			= $is_workleave_success->quota;
						$personworkleave[$i]['status'] 			= 'CN';

						$content 								= $this->dispatch(new Saving(new PersonWorkleave, $personworkleave[$i], null, new Person, $is_success->id));

						$is_personworkleave_success 			= json_decode($content);
						if(!$is_personworkleave_success->meta->success)
						{
							foreach ($is_personworkleave_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_personworkleave_success->data->id,
								'queue_morph_type'			=> get_class(new PersonWorkleave),
							]);

							$morphed->save();
						}
					}
				}

				//save Sisa Cuti
				if(!$errors->count())
				{
					$pwleave[$i]['work_id'] 				= $is_work_success->id;
					if(isset($is_personworkleave_success->id))
					{
						$pwleave[$i]['person_workleave_id'] = $is_personworkleave_success->id;
					}
					$pwleave[$i]['created_by'] 				= $pending->created_by;
					$pwleave[$i]['name'] 					= 'Pengambilan (awal) '.$is_workleave_success->name;
					$pwleave[$i]['notes'] 					= 'Auto generated dari import csv. Hanya Quota yang valid';
					
					$leave 									= $row['kuotacutitahunan'] - $row['sisacuti'];
					if($leave == 1)
					{
						$leaves 							= '';
					}
					elseif($leave==2)
					{
						$leaves 							= ' + 1 day';
					}
					else
					{
						$leaves 							= ' + '.$leave.' days';
					}

					$pwleave[$i]['start'] 					= date('Y-m-d', strtotime($work[$i]['start']));
					$pwleave[$i]['end'] 					= date('Y-m-d', strtotime($work[$i]['start'].$leaves));
					$pwleave[$i]['quota'] 					= 0 - $leave;
					$pwleave[$i]['status'] 					= 'CN';

					if($leave>0)
					{
						$content 							= $this->dispatch(new Saving(new PersonWorkleave, $pwleave[$i], null, new Person, $is_success->id));

						$is_pwleave_success 				= json_decode($content);
						if(!$is_pwleave_success->meta->success)
						{
							foreach ($is_pwleave_success->meta->errors as $key => $value) 
							{
								if(is_array($value))
								{
									foreach ($value as $key2 => $value2) 
									{
										$errors->add('Person', $value2);
									}
								}
								else
								{
									$errors->add('Person', $value);
								}
							}
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_pwleave_success->data->id,
								'queue_morph_type'			=> get_class(new PersonWorkleave),
							]);

							$morphed->save();
						}
					}
				}
				
				if(!$errors->count())
				{
					DB::commit();

					$pnumber 								= $pending->process_number+1;
					$pending->fill(['process_number' => $pnumber, 'message' => 'Sedang Menyimpan Data '.(isset($is_success->name) ? $is_success->name : '')]);
				}
				else
				{
					DB::rollback();
					
					$pending->fill(['message' => json_encode($errors)]);
				}

				$pending->save();
			}
		}

		if($errors->count())
		{
			$pending->fill(['message' => json_encode($errors)]);
		}
		else
		{
			$pending->fill(['process_number' => $pending->total_process, 'message' => 'Sukses Menyimpan Data Karyawan']);
		}

		$pending->save();

		return true;
	}
}
