<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Person;
use App\Models\PersonWorkleave;
use App\Models\Queue;
use App\Models\QueueMorph;
use App\Models\Calendar;
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
			if(floor($key/$pending->task_per_process) < $pending->total_process && !$errors->count())
			{
				$startwork 				= new DateTime( $value->start );
				if(is_null($value->end))
				{
					$endwork 			= $ended;
				}
				else
				{
					$endwork 			= new DateTime( $value->end );
				}
				
				$pwP 					= PersonWorkleave::personid($value['person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status($parameters['status'])->quota(true)->first();

				if(!$pwP)
				{
					$pwid 				= null;
				}
				else
				{
					$pwid 				= $pwP->id;
				}
				 
				$start 					= max($begin->format('Y-m-d'), $startwork->format('Y-m-d'));
		
				//if start = beginning of this year then end count one by one
				if($start == $begin->format('Y-m-d'))
				{
					$end 				= min($ended->format('Y-m-d'), date('Y-m-d'));
					$extendpolicy 		= Policy::type('extendsworkleave')->OnDate(date('Y-m-d'))->orderby('start', 'desc')->first();
					$couldbetaken 		= $begin->format('Y-m-d');
				}
				//if start != beginning of this year then end count as one (consider first year's policies)
				else
				{
					$end 				= min($ended->format('Y-m-d'), $endwork->format('Y-m-d'));
					$extendpolicy 		= Policy::type('extendsmidworkleave')->OnDate(date('Y-m-d'))->orderby('start', 'desc')->first();
					$couldbetaken 		= date('Y-m-d', strtotime($start. ' + 1 year'));
				}

				$parameters['quota']	= ((date('m', strtotime($end)) - date('m', strtotime($start)))/$workleave->quota)*12;
				$parameters['start']	= $couldbetaken;
				$parameters['end']		= date('Y-m-d', strtotime($ended->format('Y-m-d').' '.$extendpolicy->value));
				
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
				elseif(($key+1)%$pending->task_per_process==0 && !$errors->count())
				{
					$pending->fill(['process_number' => ($key+1)/$pending->task_per_process, 'message' => 'Processing']);
					$pending->save();

					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
				}
				elseif(!$errors->count())
				{
					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
				}
			}
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
	public function batchpersonstakenworkleave($id)
	{
		$queue 						= new Queue;
		$pending 					= $queue->find($id);

		$parameters 				= (array)json_decode($pending->parameter);

		$begin 						= new DateTime( $parameters['onstart'] );
		$ended 						= new DateTime( $parameters['onend'] );

		$interval 					= DateInterval::createFromDateString('1 day');
		$periods 					= new DatePeriod($begin, $interval, $ended);

		$errors 					= new MessageBag;

		//check work active on that day, please consider if that queue were written days
		$works 						= Work::active(true)->status(['contract', 'permanent'])->calendarid($parameters['calendar_id'])->get();
		$calendar 					= Calendar::id($parameters['calendar_id'])->schedulesondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->first();
		
		// if($pending->updated_at==$pending->created_at && $process_number==0)
		// {
		// 	$pending->total_task 		= count($works);
		// 	$pending->total_process 	= count($works)/10;
		// 	$pending->save();
		// }

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
			if(floor($key/$pending->task_per_process) < $pending->total_process && !$errors->count())
			{
				$startwork 				= new DateTime( $value->start );
				if(is_null($value->end))
				{
					$endwork 			= $ended;
				}
				else
				{
					$endwork 			= new DateTime( $value->end );
				}
				
				$pwP 					= PersonWorkleave::personid($value['person_id'])->ondate([$begin->format('Y-m-d'), $ended->format('Y-m-d')])->status($parameters['status'])->quota(true)->first();

				if(!$pwP)
				{
					$pwid 				= null;
				}
				else
				{
					$pwid 				= $pwP->id;
				}
				 
				
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
				elseif(($key+1)%$pending->task_per_process==0 && !$errors->count())
				{
					$pending->fill(['process_number' => ($key+1)/$pending->task_per_process, 'message' => 'Processing']);
					$pending->save();

					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
				}
				elseif(!$errors->count())
				{
					$morphed 						= new QueueMorph;

					$morphed->fill([
						'queue_id'					=> $id,
						'queue_morph_id'			=> $is_success->data->id,
						'queue_morph_type'			=> get_class(new PersonWorkleave),
					]);

					$morphed->save();
				}
			}
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
