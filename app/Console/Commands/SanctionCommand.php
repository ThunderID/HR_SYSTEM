<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\MessageBag;

use App\Console\Commands\Saving;
use App\Console\Commands\Getting;

use App\Models\Organisation;
use App\Models\Person;
use App\Models\Policy;
use App\Models\Document;
use App\Models\PersonDocument;
use App\Models\DocumentDetail;
use App\Models\AttendanceLog;
use App\Models\AttendanceDetail;

use DB, Hash;

class SanctionCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

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
		$result 		= $this->secondlock();
		
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
	 * sanction
	 *
	 * @return void
	 * @author 
	 **/
	public function secondlock()
	{
		$as = $ul = 1;
		$hp = $ht = $hc = 2;
		$settlementdate['value']	= '- 5 days';

		$search						= [];
		$sort						= [];
		$results 					= $this->dispatch(new Getting(new Organisation, $search, $sort ,1, 100));
		$contents 					= json_decode($results);

		if(!$contents->meta->success)
		{
			return true;
		}

		$organisations 				= json_decode(json_encode($contents->data), true);

		foreach ($organisations as $key => $value) 
		{
			unset($search);
			unset($sort);

			$search['name']				= 'Surat Peringatan I';
			$search['tag']				= 'SP';
			$search['withattributes']	= ['templates'];
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Document, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$sp1 					= $contents->data;
			}

			unset($search);
			unset($sort);

			$search['name']				= 'Surat Peringatan II';
			$search['tag']				= 'SP';
			$search['withattributes']	= ['templates'];
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Document, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$sp2 					= $contents->data;
			}

			unset($search);
			unset($sort);

			$search['name']				= 'Surat Peringatan III';
			$search['tag']				= 'SP';
			$search['withattributes']	= ['templates'];
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Document, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$sp3 					= $contents->data;
			}

			unset($search);
			unset($sort);
			$search['type']				= ['assplimit'];
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$as 					= $contents->data->value;
			}

			unset($search);
			unset($sort);
			$search['type']				= ['ulsplimit'];
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$ul 					= $contents->data->value;
			}

			unset($search);
			unset($sort);
			$search['type']				= ['hpsplimit'];
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$hp 					= $contents->data->value;
			}

			unset($search);
			unset($sort);
			$search['type']				= ['htsplimit'];
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$ht 					= $contents->data->value;
			}

			unset($search);
			unset($sort);
			$search['type']				= ['hcsplimit'];
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$hc 					= $contents->data->value;
			}

			unset($search);
			unset($sort);
			$search['type']				= 'secondstatussettlement';
			$search['ondate']			= date('Y-m-d');
			$search['organisationid']	= $value['id'];
			$sort						= ['created_at' => 'desc'];

			$results 					= $this->dispatch(new Getting(new Policy, $search, $sort ,1, 1));
			$contents 					= json_decode($results);

			if($contents->meta->success)
			{
				$settlementdate 		= json_decode(json_encode($contents->data), true);
			}

			$date1 						= date('Y-m-d', strtotime('first day of january '.date('Y', strtotime($settlementdate['value']))));
			$date2 						= date('Y-m-d', strtotime($settlementdate['value']));

			unset($search);
			unset($sort);
			$search['organisationid']				= $value['id'];
			$search['globalsanction']['on']			= [$date1, $date2];
			$sort									= [];

			$results 								= $this->dispatch(new Getting(new Person, $search, $sort ,1, 100));
			$contents 								= json_decode($results);

			if($contents->meta->success && isset($sp1) && isset($sp2) && isset($sp3))
			{
				$persons 							= json_decode(json_encode($contents->data), true);
				foreach ($persons as $key2 => $value2) 
				{
					//temporary consider as sp
					if($value2['HT'] >= $ht || $value2['HP'] >= $hp || $value2['HC'] >= $hc || $value2['AS'] >= $as || $value2['UL'] >= $ul)
					{
						if($value2['HT'] >= $ht)
						{
							$probs 					= 'HT';
							$quota 					= $ht;
						}
						elseif($value2['HP'] >= $hp)
						{
							$probs 					= 'HP';
							$quota 					= $hp;
						}
						elseif($value2['HC'] >= $hc)
						{
							$probs 					= 'HC';
							$quota 					= $hc;
						}
						elseif($value2['UL'] >= $ul)
						{
							$probs 					= 'UL';
							$quota 					= $ul;
						}
						else
						{
							$probs 					= 'AS';
							$quota 					= $as;
						}

						$pdoc 						= new PersonDocument;
						switch($value2['lastdoc'])
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

						$pdoc->Person()->associate(Person::find($value2['id']));
						$pdoc->save();

						foreach ($temp as $key3 => $value3) 
						{
							$pdocdet 				= new DocumentDetail;
							switch($key3)
							{
								case '0' 	:
									$pdocdet->fill(['template_id' => $value3->id, 'string' => date('Y/M/D/H/I/S').'-'.$value['code']]);
									break;
								case '1' 	:
									$pdocdet->fill(['template_id' => $value3->id, 'on' => date('Y-m-d')]);
									break;
								default 	:
									$pdocdet->fill(['template_id' => $value3->id, 'text' => 'Surat Peringatan '.($value2['lastdoc'] + 1).' Status '.$probs.'. Powered By HRIS.']);
									break;
							}
						
							$pdocdet->PersonDocument()->associate($pdoc);
							$pdocdet->save();
						}

						$adetails 					= AttendanceLog::globalsanction(['on' => [$date1, $date2], 'personid' => $value2['id']])->get();
						foreach ($adetails as $key4 => $value4) 
						{
							if($key4<$quota)
							{
								$adetail 			= new AttendanceDetail;
								$adetail->fill(['attendance_log_id' => $value4->id]);

								$adetail->PersonDocument()->associate($pdoc);
								$adetail->save();
							}
						}

					}
				}
			}
		}
	}
}
