<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Console\Commands\Getting;
use App\Console\Commands\Checking;

use Excel, Storage;
use App\Models\Person;
use App\Models\Contact;
use App\Models\MaritalStatus;
use App\Models\PersonDocument;
use App\Models\Work;
use App\Models\Chart;
use App\Models\Calendar;
use App\Models\FollowWorkleave;
use App\Models\PersonWorkleave;

class HRCheckDocumentCommand extends Command {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$sheet 							= Excel::load(storage_path('app/export_'.$this->option('example').'.csv'))->toArray();				

		foreach ($sheet as $key => $value) 
		{
			$errors 					= false;
			//check full biodata
			$person 					= Person::uniqid($value['nik'])->checkwork(true)->gender(($value['gender']=='L' ? 'male' : 'female'))->name($value['name'])->where('date_of_birth', $value['date_of_birth'])->where('place_of_birth', $value['place_of_birth'])->first();

			if(!$person)
			{
				$errors 				= true;
				$msg					= 'Kary. Nomor : '.$value['no'].' [BIODATA INVALID]';
				var_dump($msg);
			}

			//check contact
			if(!$person)
			{
				$contact 					= Contact::item('email')->value($value['email'])->personid($person['id'])->default(true)->first();

				if(!$contact)
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [EMAIL INVALID]';
					var_dump($msg);
				}
			}

			if(!$person)
			{
				$contact 					= Contact::item('address')->value($value['address'])->personid($person['id'])->default(true)->first();

				if(!$contact)
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [ADDRESS INVALID]';
					var_dump($msg);
				}
			}

			//check marital
			if(!$person)
			{
				$status 					= MaritalStatus::personid($person['id'])->status($value['marital_status'])->first();

				if(!$status)
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [MARITAL STATUS INVALID]';
					var_dump($msg);
				}
			}

			//check document
			if(!$person)
			{
				$account 					= PersonDocument::personid($person['id'])->documenttag('akun')->with(['details', 'details.template'])->first();

				if($account)
				{
					foreach ($account['details'] as $key2 => $value2) 
					{
						if($value2['template']['type']=='date')
						{
							$idx 					= 'on';
						}
						else
						{
							$idx 					= $value2['template']['type'];
						}

						$template 					= strtolower(str_replace(' ', '', $value2['template']['field']));

						if(isset($value[$template]) && $value[$template]==$value2[$idx])
						{
							//do nothing
						}
						else
						{
							$errors 				= true;
							$msg					= 'Kary. Nomor : '.$value['no'].' [AKUN INVALID]';
							var_dump($msg);
						}
					}
				}
				else
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [AKUN DOESNT EXISTS]';
					var_dump($msg);
				}
			}

			//check document pajak
			if(!$person)
			{
				$accounts 					= PersonDocument::personid($person['id'])->documenttag('pajak')->with(['details', 'details.template'])->get();

				if(!$accounts)
				{
					foreach ($accounts as $keyx => $account) 
					{
						foreach ($account['details'] as $key2 => $value2) 
						{
							if($value2['template']['type']=='date')
							{
								$idx 					= 'on';
							}
							else
							{
								$idx 					= $value2['template']['type'];
							}

							$template 					= strtolower(str_replace(' ', '', $value2['template']['field']));

							if(isset($value[$template]) && $value[$template]==$value2[$idx])
							{
								//do nothing
							}
							else
							{
								$errors 				= true;
								$msg					= 'Kary. Nomor : '.$value['no'].' [PAJAK INVALID]'.$account['document']['name'];
								var_dump($msg);
							}
						}
					}
				}
			}

			//check document ktp
			if(!$person)
			{
				$account 					= PersonDocument::personid($person['id'])->documenttag('identitas')->with(['details', 'details.template'])->first();

				if($account)
				{
					foreach ($account['details'] as $key2 => $value2) 
					{
						if($value2['template']['type']=='date')
						{
							$idx 					= 'on';
						}
						else
						{
							$idx 					= $value2['template']['type'];
						}

						$template 					= strtolower(str_replace(' ', '', $value2['template']['field']));

						if(isset($value[$template]) && $value[$template]==$value2[$idx])
						{
							//do nothing
						}
						else
						{
							$errors 				= true;
							$msg					= 'Kary. Nomor : '.$value['no'].' [IDENTITAS INVALID]';
							var_dump($msg);
						}
					}
				}
				else
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [IDENTITAS DOESNT EXISTS]';
					var_dump($msg);
				}
			}

			//check WORK & Calendar
			if(!$person)
			{
				$work 					= Work::personid($person['id'])->active(true)->status($value['statuskerja'])->first();

				if($work)
				{
					$position 			= Chart::id($work['chart_id'])->name($value['jabatan'])->tag($value['departemen'])->first();

					if(!$position)
					{
						$errors 				= true;
						$msg					= 'Kary. Nomor : '.$value['no'].' [CHART INVALID]';
						var_dump($msg);
					}

					$calendar 			= Calendar::id($work['calendar_id'])->StartHour($value['jammasukkerja'])->endhour($value['jamkeluarkerja'])->first();

					if(!$calendar)
					{
						$errors 				= true;
						$msg					= 'Kary. Nomor : '.$value['no'].' [CALENDAR INVALID]';
						var_dump($msg);
					}

					$fwleave 			= FollowWorkleave::workid($work['id'])->wherehas('workleave', function($q)use($value){$q->where('quota', $value['quota']);})->first();

					if(!$fwleave)
					{
						$errors 				= true;
						$msg					= 'Kary. Nomor : '.$value['no'].' [WLEAVE QUOTA INVALID]';
						var_dump($msg);
					}

					$wleave 			= PersonWorkleave::personid($person['id'])->sum('quota');

					if($wleave != $value['sisacuti'])
					{
						$errors 				= true;
						$msg					= 'Kary. Nomor : '.$value['no'].' [WLEAVE LEFT INVALID] '.$wleave;
						var_dump($msg);
					}
				}
				else
				{
					$errors 				= true;
					$msg					= 'Kary. Nomor : '.$value['no'].' [WORK INVALID]';
					var_dump($msg);
				}
			}
		}
		return true;

		//
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

}
