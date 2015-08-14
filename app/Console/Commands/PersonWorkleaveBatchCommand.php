<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\Queue;
use App\Models\QueueMorph;
use \Illuminate\Support\MessageBag as MessageBag;
use DateTime, DateInterval, DatePeriod;

class PersonWorkleaveBatchCommand extends Command {

	use \Illuminate\Foundation\Bus\DispatchesCommands;
	use \Illuminate\Foundation\Validation\ValidatesRequests;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hr:personworkleavebatch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Batch Person Workleave Command.';

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
		$id 			= $this->option('queueid');

		$result 		= $this->batchpersonworkleave($id);

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
			['argument', InputArgument::REQUIRED, 'An example argument.'],
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
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchpersonworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= (array)json_decode($pending->parameter);

		$begin 						= new DateTime( $parameters['onstart'] );
		$ended 						= new DateTime( $parameters['onend'] );

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$pwP 						= PersonWorkleave::personid($parameters['associate_person_id'])->ondate([$begin, $ended])->status($parameters['status'])->quota(true)->first();
		
		if(!$pwP)
		{
			$pwid 					= null;
		}
		else
		{
			$pwid 					= $pwP->id;
		}

		$wd							= ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday', 'monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];

		$total_l 					= 0;
		$date_l 					= [];
		$workdays 					= [];

		$workday 					= explode(',', $parameters['workcalendars']['workdays']);
		
		foreach ($workday as $key2 => $value2) 
		{
			if($value2!='')
			{
				$value2 			= str_replace(' ', '', $value2);
				$workdays[]			= $wd[strtolower($value2)];
			}
		}

		foreach ( $periods as $period )
		{
			if($parameters['workcalendars']['schedules'])
			{
				foreach ($parameters['workcalendars']['schedules'] as $key3 => $value3) 
				{
					if(date('Y-m-d', strtotime($value3['on'])) == $period->format('Y-m-d') && strtoupper($value3['status'])=='L')
					{
						$date_l[]	= $period->format('Y-m-d');
					}
				}
			}

			if(!in_array($period->format('Y-m-d'), $date_l))
			{
				if(in_array(strtolower($period->format('l')), $workdays))
				{
					$total_l++;
				}

				$date_l[]			= $period->format('Y-m-d');
			}
		}

		$attributes['quota']		= 0 - (int)$total_l;

		$content 					= $this->dispatch(new Saving(new PersonWorkleave, $parameters, $pwid, new Person, $parameters['associate_person_id']));
		$is_success 				= json_decode($content);

		if(!$is_success->meta->success)
		{
			foreach ($is_success->meta->errors as $key => $value) 
			{
				if(is_array($value))
				{
					foreach ($value as $key2 => $value2) 
					{
						$errors->add('Batch', $value2);
					}
				}
				else
				{
					$errors->add('Batch', $value);
				}
			}
		}
		else
		{
			$morphed 						= new QueueMorph;

			$morphed->fill([
				'queue_id'					=> $id,
				'queue_morph_id'			=> $is_success->data->id,
				'queue_morph_type'			=> get_class(new PersonWorkleave),
			]);

			$morphed->save();
		}

		if(!$errors->count())
		{
			$pending->fill(['process_number' => $pending->total_process, 'message' => 'Success']);
		}
		else
		{
			$pending->fill(['message' => json_encode($errors)]);
		}

		$pending->save();

		return true;

	}

}
