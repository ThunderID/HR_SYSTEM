<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\Organisation;
use App\Models\Policy;
use App\Models\Person;
use App\Models\Queue;

use Log, DB;

class SanctionQueueCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:sanctionqueue';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate queue for batch sanction of attendance log.';

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
		$result 		= $this->generatsanction();
		
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
	 * lock around 5 days
	 *
	 * @return void
	 * @author 
	 **/
	public function generatsanction()
	{
		Log::info('Running Sanction command @'.date('Y-m-d H:i:s'));

		$organisations 						= Organisation::get();

		foreach ($organisations as $key => $value) 
		{
			//check document
			$as = $ul = 1;
			$hp = $ht = $hc = 2;
			$settlementdate	= '- 5 days';

			$policy 					= Policy::organisationid($value['id'])->type('assplimit')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
	
			if($policy)
			{
				$as 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('ulsplimit')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
	
			if($policy)
			{
				$ul 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('hpsplimit')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$hp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('htsplimit')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$ht 					= $policy->value;
			}

		
			$policy 					= Policy::organisationid($value['id'])->type('hcsplimit')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$hc 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('asid')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();

			if($policy)
			{
				$assp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('ulid')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
	
			if($policy)
			{
				$ulsp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('hpid')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$hpsp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('htid')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$htsp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('hcid')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$hcsp 					= $policy->value;
			}

			$policy 					= Policy::organisationid($value['id'])->type('secondstatussettlement')->ondate(date('Y-m-d H:i:s'))->orderby('started_at', 'asc')->first();
			
			if($policy)
			{
				$settlementdate 		= $policy->value;
			}

			$date1 						= date('Y-m-d', strtotime('first day of january '.date('Y', strtotime($settlementdate))));
			$date2 						= date('Y-m-d', strtotime($settlementdate));

			$persons 					= Person::organisationid($value['id'])->globalsanction(['on' => [$date1, $date2]])->get();

			DB::beginTransaction();

			$parameter['name']				= 'Sanction Attendance '.$date1.' - '.$date2;
			$parameter['ondate']			= [$date1, $date2];
			$parameter['organisation_id']	= $value['id'];
			$parameter['as']				= $as;
			$parameter['ul']				= $ul;
			$parameter['hp']				= $hp;
			$parameter['ht']				= $ht;
			$parameter['hc']				= $hc;
			$parameter['assp']				= $assp;
			$parameter['ulsp']				= $ulsp;
			$parameter['hpsp']				= $hpsp;
			$parameter['htsp']				= $htsp;
			$parameter['hcsp']				= $hcsp;

			$queue 							= new Queue;
			$queue->fill([
					'process_name' 			=> 'hr:sanction',
					'parameter' 			=> json_encode($parameter),
					'total_process' 		=> count($persons),
					'task_per_process' 		=> 1,
					'process_number' 		=> 0,
					'total_task' 			=> count($persons),
					'message' 				=> 'Initial Commit',
			]);

			if(!$queue->save())
			{
				DB::rollback();

				Log::error('Save queue on sanction command '.json_encode($queue->getError()));
			}
			else
			{
				DB::Commit();
			}
		}
	}
}
