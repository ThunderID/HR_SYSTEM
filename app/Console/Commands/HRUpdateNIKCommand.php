<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Person;
use App\Models\Organisation;
use DB;

class HRUpdateNIKCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:updatenik';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update NIK format from AJR.YEAR.NUMBER to AJRYEAR.NUMBER.';

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
		$result 		= $this->updatenik();

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
	 * UPDATE NIK FORMAT
	 *
	 * @return void
	 * @author 
	 **/
	public function updatenik()
	{
		DB::beginTransaction();

		$organisations 			= \App\Models\Organisation::get();

		// //simpan nik lama
		// foreach ($organisations as $key => $value) 
		// {
		// 	$document 			= new \App\Models\Document;

		// 	$document->fill(['name' => 'NIK V1', 'tag' => 'NIK V1', 'is_required' => false]);

		// 	$document->organisation()->associate($value);

		// 	if($document->save())
		// 	{
		// 		$template 		= new \App\Models\Template;

		// 		$template->fill(['field' => 'nik', 'type' => 'string']);

		// 		if(!$template->save())
		// 		{
		// 			$this->info("Error Save Template");

		// 			return false;
		// 		}
		// 	}
		// 	else
		// 	{
		// 		$this->info("Error Save Dokumen");

		// 		return false;
		// 	}

		// 	//simpan uniqid person
		// 	foreach ($value->persons as $key2 => $value2) 
		// 	{
		// 		$pdocs 				= new \App\Models\PersonDocument;

		// 		$pdocs->fill(['document_id' => $document->id]);

		// 		$pdocs->person()->associate($value2);

		// 		if($pdocs->save())
		// 		{
		// 			$docdetail 		= new \App\Models\DocumentDetail;

		// 			$docdetail->fill(['template_id' => $template->id, 'string' => $value2->uniqid]);

		// 			$docdetail->persondocument()->associate($pdocs);

		// 			if(!$docdetail->save())
		// 			{
		// 				$this->info("Error Save Template person");

		// 				return false;
		// 			}
		// 		}
		// 		else
		// 		{
		// 			$this->info("Error Save document person");

		// 			return false;
		// 		}

		// 		//reset db
		// 		$value2->fill(['uniqid' => '']);

		// 		if(!$value2->save())
		// 		{
		// 			dd($value2->getError());
		// 			$this->info("Cannot save nik ".$value2->name.' ORG'.$value->name);

		// 			return false;
		// 		}
		// 	}
		// }

		//generate nik baru
		foreach ($organisations as $key => $value) 
		{
			//simpan uniqid person
			foreach ($value->persons as $key2 => $value2) 
			{
				// check code org
				$nik 				= $value->code;
				// joined year
				$work 				= \App\Models\Work::personid($value2['id'])->orderby('start', 'asc')->first();
				if($work)
				{
					$nik 			= $nik.date('y', strtotime($work->start));

					//ordering
					$prev_nik 			= (\App\Models\Person::where('uniqid', 'like', $nik.'.%')->count()) + 1;

					$nik 				= $nik.'.'.str_pad($prev_nik,3,"0",STR_PAD_LEFT);

					$person 			= \App\Models\Person::find($value2['id']);

					$person->fill(['uniqid' => $nik]);

					if(!$person->save())
					{
						dd($person->getError());
						$this->info("Cannot save nik ".$person->name.' ORG'.$value->name);

						return false;
					}
				}
			}
		}

		DB::commit();

		return true;

		// $organisation 			= Organisation::get();

		// foreach ($organisation as $key => $value) 
		// {
		// 	$this->info("Updating ".$value->code);

		// 	$persons 			= Person::organisationid($value->id)->get();
		// 	foreach ($persons as $key2 => $value2) 
		// 	{
		// 		$new_nik 		= str_replace(strtoupper($value2->organisation->code).'.', strtoupper($value2->organisation->code), $value2->uniqid);
				
		// 		$value2->fill(['uniqid' => $new_nik]);

		// 		if (!$value2->save())
		// 		{
		// 			$this->info("Failed ".$value2->getError());
		// 		}

		// 		if(($key2+1)%10==0)
		// 		{
		// 			$this->info("Progress ".($key2+1)." Dari ".count($persons));
		// 		}
		// 	}
		// }

		return true;
	}
}
