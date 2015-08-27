<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

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
				$messages 						= json_decode($pending->message, true);
				
				DB::beginTransaction();

				$errors 						= new MessageBag();

				if(!isset($organisation) || $organisation['code']!=$row['kodeorganisasi'])
				{
					$organisation 				= Organisation::code($row['kodeorganisasi'])->first();

					if(!$organisation)
					{
						$errors->add('Batch', 'Bisnis unit tidak terdaftar.');
					}
				}

				if(!$errors->count())
				{
					$person 					= Person::uniqid($row['nik'])->first();

					if(!$person)
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
							$uname 							= Person::username($modifyuname.'.'.$row['kodeorganisasi'])->first();

							if($uname)
							{
								if(isset($originaluname[count($originaluname)-1]))
								{
									$modifyuname 			= $modifyuname.$originaluname[count($originaluname)-1][$idxuname];
								}
								else
								{
									$modifyuname 			= $modifyuname.$modifyuname;
								}

								$idxuname++;
							}
						}
						while($uname);

						$attributes[$i]['username']			= $modifyuname.'.'.$row['kodeorganisasi'];

						$attributes[$i]['password']			= Hash::make($row['nik']);
						$attributes[$i]['place_of_birth']	= $row['tempatlahir'];
						$attributes[$i]['last_password_updated_at'] = date('Y-m-d H:i:s', strtotime('- 3 month'));

						list($d, $m, $y) 					= explode("/", $row['tanggallahir']);
						$attributes[$i]['date_of_birth']	= date('Y-m-d', strtotime("$y-$m-$d"));

						$attributes[$i]['gender']			= $row['gender']=='L' ? 'male' : 'female';
						$attributes[$i]['org_id']			= $org_id_code;

						$is_success 						= new Person;
						$is_success->fill($attributes[$i]);
						$is_success->Organisation()->associate($organisation);

						if(!$is_success->save())
						{
							$errors->add('Batch', $is_success->getError());
						}
						else
						{
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
						$errors->add('Batch', 'NIK sudah terdaftar');
					}
				}

				//save contact
				if(!$errors->count())
				{
					if(!is_null($row['email']))
					{
						$email[$i]['item']					= 'email';
						$email[$i]['value']					= $row['email'];
						$email[$i]['is_default']			= true;

						$is_mail_success 					= new Contact;
						$is_mail_success->fill($email[$i]);
						$is_mail_success->Person()->associate($is_success);

						if(!$is_mail_success->save())
						{
							$errors->add('Batch', $is_mail_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_mail_success->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}

					if(!is_null($row['phone']))
					{
						$phone[$i]['item']					= 'phone';
						$phone[$i]['value']					= $row['phone'];
						$phone[$i]['is_default']			= true;

						$is_phone_success 					= new Contact;
						$is_phone_success->fill($phone[$i]);
						$is_phone_success->Person()->associate($is_success);

						if(!$is_phone_success->save())
						{
							$errors->add('Batch', $is_phone_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_phone_success->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}


					if(!is_null($row['alamat']))
					{
						$address[$i]['item']				= 'address';
						$address[$i]['value']				= $row['alamat'];
						$address[$i]['is_default']			= true;

						$is_address_success 				= new Contact;
						$is_address_success->fill($address[$i]);
						$is_address_success->Person()->associate($is_success);

						if(!$is_address_success->save())
						{
							$errors->add('Batch', $is_address_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_address_success->id,
								'queue_morph_type'			=> get_class(new Contact),
							]);

							$morphed->save();
						}
					}
				}

				//save marital statuses
				if(!$errors->count())
				{
					$marital[$i]['status']					= $row['statuskawin'];
					$marital[$i]['on']						= date('Y-m-d H:i:s');


					$is_marital_success 					= new MaritalStatus;
					$is_marital_success->fill($marital[$i]);
					$is_marital_success->Person()->associate($is_success);

					if(!$is_marital_success->save())
					{
						$errors->add('Batch', $is_marital_success->getError());
					}
					else
					{
						$morphed 							= new QueueMorph;

						$morphed->fill([
							'queue_id'						=> $pending->id,
							'queue_morph_id'				=> $is_marital_success->id,
							'queue_morph_type'				=> get_class(new MaritalStatus),
						]);

						$morphed->save();
					}
				}

				//save ktp
				if(!$errors->count())
				{
					$document 								= Document::organisationid($organisation['id'])->name('KTP')->tag('Identitas')->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen ktp');
					}
					else
					{
						$ktp[$i]['document_id']				= $document['id'];

						$is_ktp_success 					= new PersonDocument;
						$is_ktp_success->fill($ktp[$i]);
						$is_ktp_success->Person()->associate($is_success);

						if(!$is_ktp_success->save())
						{
							$errors->add('Batch', $is_ktp_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_ktp_success->id,
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
								$is_ktpd_success 			= new DocumentDetail;
								$is_ktpd_success->fill($ktpdetail[$i]);
								$is_ktpd_success->PersonDocument()->associate($is_ktp_success);

								if(!$is_ktpd_success->save())
								{
									$errors->add('Batch', $is_ktpd_success->getError());
								}
								else
								{
									$morphed 				= new QueueMorph;

									$morphed->fill([
										'queue_id'			=> $pending->id,
										'queue_morph_id'	=> $is_ktpd_success->id,
										'queue_morph_type'	=> get_class(new DocumentDetail),
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
					$document 								= Document::organisationid($organisation['id'])->name('Akun Bank')->tag('Akun')->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen bank');
					}
					else
					{
						$bank[$i]['document_id']			= $document['id'];

						$is_bank_success 					= new PersonDocument;
						$is_bank_success->fill($bank[$i]);
						$is_bank_success->Person()->associate($is_success);

						if(!$is_bank_success->save())
						{
							$errors->add('Batch', $is_bank_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bank_success->id,
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
								$is_bankd_success 					= new DocumentDetail;
								$is_bankd_success->fill($bankdetail[$i]);
								$is_bankd_success->PersonDocument()->associate($is_bank_success);

								if(!$is_bankd_success->save())
								{
									$errors->add('Batch', $is_bankd_success->getError());
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bankd_success->id,
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
					$document 								= Document::organisationid($organisation['id'])->name('NPWP')->tag('Pajak')->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen bank');
					}
					else
					{
						$npwp[$i]['document_id']			= $document['id'];

						$is_npwp_success 					= new PersonDocument;
						$is_npwp_success->fill($npwp[$i]);
						$is_npwp_success->Person()->associate($is_success);

						if(!$is_npwp_success->save())
						{
							$errors->add('Batch', $is_npwp_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_npwp_success->id,
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
								$is_npwpd_success 					= new DocumentDetail;
								$is_npwpd_success->fill($npwpdetail[$i]);
								$is_npwpd_success->PersonDocument()->associate($is_npwp_success);

								if(!$is_npwpd_success->save())
								{
									$errors->add('Batch', $is_npwpd_success->getError());
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_npwpd_success->id,
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
					$document 								= Document::organisationid($organisation['id'])->name('BPJS Ketenagakerjaan')->tag('Pajak')->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen bank');
					}
					else
					{
						$bpjstk[$i]['document_id']			= $document['id'];

						$is_bpjstk_success 					= new PersonDocument;
						$is_bpjstk_success->fill($bpjstk[$i]);
						$is_bpjstk_success->Person()->associate($is_success);

						if(!$is_bpjstk_success->save())
						{
							$errors->add('Batch', $is_bpjstk_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bpjstk_success->id,
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
								$is_bpjstkd_success 					= new DocumentDetail;
								$is_bpjstkd_success->fill($bpjstkdetail[$i]);
								$is_bpjstkd_success->PersonDocument()->associate($is_bpjstk_success);

								if(!$is_bpjstkd_success->save())
								{
									$errors->add('Batch', $is_bpjstkd_success->getError());
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bpjstkd_success->id,
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
					$document 								= Document::organisationid($organisation['id'])->name('BPJS Kesehatan')->tag('Pajak')->withattributes(['templates'])->first();

					if(!$document)
					{
						$errors->add('Batch', 'Tidak ada dokumen bank');
					}
					else
					{
						$bpjsk[$i]['document_id']			= $document['id'];

						$is_bpjsk_success 					= new PersonDocument;
						$is_bpjsk_success->fill($bpjsk[$i]);
						$is_bpjsk_success->Person()->associate($is_success);

						if(!$is_bpjsk_success->save())
						{
							$errors->add('Batch', $is_bpjsk_success->getError());
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_bpjsk_success->id,
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
								$is_bpjskd_success 					= new DocumentDetail;
								$is_bpjskd_success->fill($bpjskdetail[$i]);
								$is_bpjskd_success->PersonDocument()->associate($is_bpjsk_success);

								if(!$is_bpjskd_success->save())
								{
									$errors->add('Batch', $is_bpjskd_success->getError());
								}
								else
								{
									$morphed 						= new QueueMorph;

									$morphed->fill([
										'queue_id'					=> $pending->id,
										'queue_morph_id'			=> $is_bpjskd_success->id,
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
					$is_calendar_success 					= Calendar::organisationid($organisation['id'])->starthour(date('H:i:s', strtotime($row['jammasukkerja'])))->endhour(date('H:i:s', strtotime($row['jampulangkerja'])))->first();

					if(!$is_calendar_success)
					{
						$calendar[$i]['import_from_id'] 	= 1;
						$calendar[$i]['name'] 				= 'Costum Kalender Untuk '.$row['namacabang'];
						$calendar[$i]['workdays'] 			= 'senin,selasa,rabu,kamis,jumat';
						$calendar[$i]['start'] 				= date('H:i:s', strtotime($row['jammasukkerja']));
						$calendar[$i]['end'] 				= date('H:i:s', strtotime($row['jampulangkerja']));

						$is_calendar_success 				= new Calendar;
						$is_calendar_success->fill($calendar[$id]);
						$is_calendar_success->Organisation()->associate($organisation);
						$is_calendar_success 				= json_decode($content);
						
						if(!$is_calendar_success->save())
						{
							$errors->add('Batch', $is_calendar_success->getError());
						}
						else
						{
							$morphed 							= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_calendar_success->id,
								'queue_morph_type'			=> get_class(new Calendar),
							]);

							$morphed->save();
						}
					}
				}

				//save branch
				if(!$errors->count())
				{
					$is_branch_success 					= Branch::organisationid($organisation['id'])->name($row['namacabang'])->first();

					if(!$is_branch_success)
					{
						$branch[$i]['name'] 			= $row['namacabang'];

						$is_branch_success 				= new Branch;
						$is_branch_success->fill($branch[$id]);
						$is_branch_success->Organisation()->associate($organisation);
						$is_branch_success 				= json_decode($content);
						
						if(!$is_branch_success->save())
						{
							$errors->add('Batch', $is_branch_success->getError());
						}
						else
						{
							$morphed 							= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_branch_success->id,
								'queue_morph_type'			=> get_class(new Branch),
							]);

							$morphed->save();
						}
					}
				}

				//save Chart
				if(!$errors->count())
				{
					$is_chart_success 					= Chart::organisationid($organisation['id'])->branchid($is_branch_success->id)->tag($row['namadepartemen'])->name($row['jabatan'])->first();

					if(!$is_chart_success)
					{
						$chart[$i]['name'] 				= $row['jabatan'];
						$chart[$i]['tag'] 				= $row['namadepartemen'];
						$chart[$i]['min_employee'] 		= 10;
						$chart[$i]['ideal_employee'] 	= 10;
						$chart[$i]['max_employee'] 		= 10;

						$is_chart_success 				= new Chart;
						$is_chart_success->fill($chart[$id]);
						$is_chart_success->Branch()->associate($organisation);
						$is_chart_success 				= json_decode($is_branch_success);
						
						if(!$is_chart_success->save())
						{
							$errors->add('Batch', $is_chart_success->getError());
						}
						else
						{
							$morphed 							= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_chart_success->id,
								'queue_morph_type'			=> get_class(new Chart),
							]);

							$morphed->save();
						}
					}
				}

				//save Follow
				if(!$errors->count())
				{
					$is_follow_success 						= Follow::chartid($is_chart_success->id)->calendarid($is_calendar_success->id)->first();

					if(!$is_follow_success->meta->success)
					{
						$follow[$i]['chart_id'] 			= $is_chart_success->id;

						$is_follow_success 					= new Follow;
						$is_follow_success->fill($follow[$i]);
						$is_follow_success->Calendar()->associate($is_calendar_success);

						if(!$is_follow_success->save())
						{
							$errors->add('Batch', 'Tidak dapat menyimpan jadwal kerja');
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
					$is_workleave_success 					= Workleave::organisationid($organisation['id'])->quota($row['kuotacutitahunan'])->status('CN')->active(true)->first();

					if(!$is_workleave_success)
					{
						$workleave[$i]['name'] 				= 'Custom Cuti '.$row['namacabang'];
						$workleave[$i]['quota'] 			= $row['kuotacutitahunan'];
						$workleave[$i]['status'] 			= 'CN';
						$workleave[$i]['is_active'] 		= true;

						$is_workleave_success 				= new Workleave;
						$is_workleave_success->fill($workleave[$i]);
						$is_workleave_success->Organisation()->associate($organisation);

						if(!$is_workleave_success->save())
						{
							$errors->add('Batch', 'Tidak dapat menyimpan quota cuti');
						}
						else
						{
							$morphed 						= new QueueMorph;

							$morphed->fill([
								'queue_id'					=> $pending->id,
								'queue_morph_id'			=> $is_workleave_success->id,
								'queue_morph_type'			=> get_class(new Workleave),
							]);

							$morphed->save();
						}
					}
				}

				//save Work
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

					$is_work_success 						= Work::chartid($is_chart_success->id)->calendarid($is_calendar_success->id)->personid($is_success->id)->status($search['status'])->first();

					if(!$is_work_success)
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

				//save FolloWorkleve
				if(!$errors->count())
				{
					$is_fwleave_success 						= FollowWorkleave::workid($is_work_success->id)->workleaveid($is_workleave_success->id)->first();

					if(!$is_fwleave_success->meta->success)
					{
						$fwleave[$i]['work_id'] 				= $is_work_success->id;

						$is_fwleave_success 					= new FollowWorkleave;
						$is_fwleave_success->fill($fwleave[$i]);
						$is_fwleave_success->Workleave()->associate($is_workleave_success);

						if(!$is_fwleave_success->save())
						{
							$errors->add('Batch', 'Tidak dapat menyimpan kuota cuti tahunan.');
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
						$personworkleave[$i]['end'] 			= date('Y-m-d', strtotime('last day of last month'));
						$personworkleave[$i]['quota'] 			= $is_workleave_success->quota;
						$personworkleave[$i]['status'] 			= 'CN';

						$is_personworkleave_success 			= new PersonWorkleave;
						$is_personworkleave_success->fill($personworkleave[$i]);
						$is_personworkleave_success->Person()->associate($is_success);

						if(!$is_personworkleave_success->save())
						{
							$errors->add('Batch', $is_personworkleave_success->getError());
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
						$is_pwleave_success 				= new PersonWorkleave;
						$is_pwleave_success->fill($pwleave[$i]);
						$is_pwleave_success->Person()->associate($is_success);

						if(!$is_pwleave_success->save())
						{
							$errors->add('Batch', $is_pwleave_success->getError());
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
					$messages['message'][$pnumber]		 	= 'Sukses Menyimpan Data Karyawan '.(isset($parameters['namalengkap']) ? $parameters['namalengkap'] : '');
					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}
				else
				{
					DB::rollback();
					
					$pnumber 								= $pending->process_number+1;
					$messages['message'][$pnumber] 			= 'Gagal Menyimpan Data Karyawan '.(isset($parameters['namalengkap']) ? $parameters['namalengkap'] : '');
					$messages['errors'][$pnumber] 			= $errors;

					$pending->fill(['process_number' => $pnumber, 'message' => json_encode($messages)]);
				}

				$pending->save();
			}
		}

		return true;
	}
}
