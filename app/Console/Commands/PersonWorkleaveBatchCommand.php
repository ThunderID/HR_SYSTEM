<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \Illuminate\Support\MessageBag as MessageBag;

use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Calendar;
use App\Models\Workleave;
use App\Models\Policy;
use App\Models\Work;

use DateTime, DateInterval, DatePeriod, DB;

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
		$id 			= $this->argument()['queueid'];
		if($this->option('queuefunc'))
		{
			$queuefunc 	= $this->option('queuefunc');
			switch ($queuefunc) 
			{
				case 'personsgiven':
					$result = $this->batchpersonsgivenworkleave($id);
					break;
				
				case 'personstaken':
					$result = $this->batchpersonstakenworkleave($id);
					break;
				
				default:
					$result = $this->batchpersonworkleave($id);
					break;
			}
		}
		else
		{
			$result 		= $this->batchpersonworkleave($id);
		}

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
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchpersonworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= json_decode($pending->parameter, true);

		$begin 						= new DateTime( $parameters['start'] );
		$ended 						= new DateTime( $parameters['end'] );

		$interval 					= DateInterval::createFromDateString('1 day');
		$periods 					= new DatePeriod($begin, $interval, $ended);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$pwP 						= PersonWorkleave::personid($parameters['associate_person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status($parameters['status'])->quota(false)->first();
		
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

		$workday 					= explode(',', $parameters['workscalendars']['workdays']);
		
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
			if($parameters['workscalendars']['schedules'])
			{
				foreach ($parameters['workscalendars']['schedules'] as $key3 => $value3) 
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

		$parameters['quota']		= 0 - (int)$total_l;

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

	/**
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchpersonsgivenworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= (array)json_decode($pending->parameter);

		$begin 						= new DateTime( $parameters['onstart'] );
		$ended 						= new DateTime( $parameters['onend'] );

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$works 						= Work::active(true)->status(['contract', 'permanent'])->workleaveid($parameters['workleave_id'])->get();
		$workleave 					= Workleave::id($parameters['workleave_id'])->first();

		foreach ($works as $key => $value) 
		{

			if(($pending->process_number-1) < $key)
			{
				DB::beginTransaction();
				
				$pwP 					= PersonWorkleave::personid($value['person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status($parameters['status'])->quota(true)->first();

				if(!$pwP)
				{
					$pwid 				= null;
				}
				else
				{
					$pwid 				= $pwP->id;
				}
				 
				$parameters['work_id']	= $value->id;
				$parameters['quota']	= $workleave->quota;
				$parameters['start']	= $begin->format('Y-m-d');
				$parameters['end']		= $ended->format('Y-m-d');
				
				$content 				= $this->dispatch(new Saving(new PersonWorkleave, $parameters, $pwid, new Person, $value->person_id));
				$is_success 			= json_decode($content);

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
				
				if(!$errors->count())
				{
					DB::commit();

					$pending->fill(['process_number' => ($pending->process_number+1), 'message' => 'Working']);

					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
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
			$pending->fill(['process_number' => ($pending->total_task), 'message' => 'Success']);
		}

		$pending->save();

		return true;

	}

	/**
	 * update 1st version
	 *
	 * @return void
	 * @author 
	 **/
	public function batchpersonstakenworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= (array)json_decode($pending->parameter);

		$begin 						= new DateTime( $parameters['on'] );
		$ended 						= new DateTime( $parameters['on'].' + 1 day' );

		$interval 					= DateInterval::createFromDateString('1 day');
		$periods 					= new DatePeriod($begin, $interval, $ended);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$works 						= Work::active(true)->status(['contract', 'permanent'])->calendarid($parameters['associate_calendar_id'])->get();
		$calendar 					= Calendar::id($parameters['associate_calendar_id'])->schedulesondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->first();

		$wd							= ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday', 'monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];

		$total_l 					= 0;
		$date_l 					= [];
		$workdays 					= [];

		$workday 					= explode(',', $calendar['workdays']);
		
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
			if($calendar['schedules'])
			{
				foreach ($calendar['schedules'] as $key3 => $value3) 
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

		$parameters['quota']		= 0 - (int)$total_l;

		foreach ($works as $key => $value) 
		{

			if(($pending->process_number-1) < $key)
			{
				DB::beginTransaction();
				
				$startwork 				= new DateTime( $value->start );
				if(is_null($value->end))
				{
					$endwork 			= $ended;
				}
				else
				{
					$endwork 			= new DateTime( $value->end );
				}
				
				$pwG 					= PersonWorkleave::personid($value['person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status('CN')->quota(true)->first();
				$pw 					= PersonWorkleave::personid($value['person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status($parameters['status'])->quota(false)->first();

				if(!$pw)
				{
					$pid 				= null;
				}
				else
				{
					$pid 				= $pw->id;
				}
				 
				$attributes 						= $parameters;
				$attributes['start'] 				= $parameters['on'];
				$attributes['end'] 					= $parameters['on'];
				$attributes['work_id'] 				= $value->id;

				if($pwG)
				{
					$attributes['person_workleave_id'] 	= $pwG->id;
				}


				$content 				= $this->dispatch(new Saving(new PersonWorkleave, $attributes, $pid, new Person, $value->person_id));
				$is_success 			= json_decode($content);

				if(!$is_success->meta->success)
				{
					foreach ($is_success->meta->errors as $key2 => $value2) 
					{
						if(is_array($value2))
						{
							foreach ($value2 as $key3 => $value3) 
							{
								$errors->add('Batch', $value3);
							}
						}
						else
						{
							$errors->add('Batch', $value2);
						}
					}
				}
				
				if(!$errors->count())
				{
					DB::commit();

					$pending->fill(['process_number' => ($pending->process_number+1), 'message' => 'Working']);

					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
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
			$pending->fill(['process_number' => ($pending->total_task), 'message' => 'Success']);
		}

		$pending->save();

		return true;
	}
}
