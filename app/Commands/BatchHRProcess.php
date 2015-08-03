<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Input, Session, App, Paginator, Redirect, DB, Config, DateInterval, DatePeriod, DateTime;
use Illuminate\Support\MessageBag;
use App\Console\Commands\Saving;
use App\Console\Commands\Getting;
use App\Models\Chart;
use App\Models\Person;
use App\Models\Workleave;
use App\Models\PersonWorkleave;

class BatchHRProcess extends Command implements SelfHandling, ShouldBeQueued {
	use DispatchesCommands, ValidatesRequests;

	// use InteractsWithQueue, SerializesModels;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($input)
	{
		//
		$this->input 							= $input;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$errors 								= new MessageBag();

		$input 									= $this->input;

		$start 									= date('Y',strtotime($input['start']));
		$begin 									= new DateTime( Input::get('joint_start') );
		$end 									= new DateTime( Input::get('joint_end').' + 1 day' );

		if($start < date('Y') || $begin->format('Y') < date('Y') || $end->format('Y') < date('Y'))
		{
			$errors->add('Workleave', 'Batch pemberian cuti hanya dapat dilakukan untuk tahun berikut.');
		}

		$search['id'] 							= $input['workleave_id'];
		$search['organisationid'] 				= $input['org_id'];
		$search['withattributes'] 				= ['organisation'];
		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Workleave, $search, $sort , 1, 1));
		$contents 								= json_decode($results);
		$workleave 								= json_decode(json_encode($contents->data), true);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		unset($search);
		unset($sort);

		$search['organisationid'] 				= $input['org_id'];
		$search['WorkCalendar'] 				= true;
		$search['WithWorkCalendarSchedules'] 	= ['on' => [$begin->format('Y-m-d'), $end->format('Y-m-d')]];

		$sort 									= ['name' => 'asc'];
		$results 								= $this->dispatch(new Getting(new Person, $search, $sort , 1, 100));
		$contents 								= json_decode($results);

		if(!$contents->meta->success)
		{
			App::abort(404);
		}

		$persons 								= json_decode(json_encode($contents->data), true);

		$interval 								= DateInterval::createFromDateString('1 day');
		$periods 								= new DatePeriod($begin, $interval, $end);
		$wd										= ['senin' => 'monday', 'selasa' => 'tuesday', 'rabu' => 'wednesday', 'kamis' => 'thursday', 'jumat' => 'friday', 'sabtu' => 'saturday', 'minggu' => 'sunday', 'monday' => 'monday', 'tuesday' => 'tuesday', 'wednesday' => 'wednesday', 'thursday' => 'thursday', 'friday' => 'friday', 'saturday' => 'saturday', 'sunday' => 'sunday'];

		DB::beginTransaction();

		foreach ($persons as $key => $value) 
		{
			if(isset($value['workscalendars'][0]))
			{
				$entry_date 					= max(date('Y-m-d', strtotime('First day of January '.$start)), date('Y-m-d', strtotime($value['workscalendars'][0]['start'])));
				$total_l 						= 0;
				$date_l 						= [];
				$workdays 						= [];
		
				$workday 						= explode(',', $value['workscalendars'][0]['calendar']['workdays']);

				foreach ($workday as $key2 => $value2) 
				{
					if($value2!='')
					{
						$value2 				= str_replace(' ', '', $value2);
						$workdays[]				= $wd[strtolower($value2)];
					}
				}

				foreach ( $periods as $period )
				{
					if($value['workscalendars'][0]['calendar']['schedules'])
					{
						foreach ($value['workscalendars'][0]['calendar']['schedules'] as $key3 => $value3) 
						{
							if(date('Y-m-d', strtotime($value3['on'])) == $period->format('Y-m-d') && strtoupper($value3['status'])=='L')
							{
								$date_l[]			= $period->format('Y-m-d');
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

				$attributes1['work_id'] 		= $value['workscalendars'][0]['id'];
				$attributes1['workleave_id'] 	= $input['workleave_id'];
				$attributes1['created_by'] 		= Session::get('loggedUser');
				$attributes1['start'] 			= date('Y-m-d', strtotime($entry_date. ' + 1 year'));
				$attributes1['end'] 			= date('Y-m-d', strtotime(' last day of december '.date('Y', strtotime($attributes1['start'])).' + 3 months'));
				$attributes1['name'] 			= $workleave['name'].' '.date('Y', strtotime($attributes1['start']));
				$attributes1['quota'] 			= $workleave['quota'];
				$attributes1['status'] 			= 'CN';
				$attributes1['notes'] 			= 'Batch '.$attributes1['name'];

				$content 						= $this->dispatch(new Saving(new PersonWorkleave, $attributes1, null, new Person, $value['id']));
				$is_success 					= json_decode($content);

				if(!$is_success->meta->success)
				{
					foreach ($is_success->meta->errors as $key => $value) 
					{
						if(is_array($value))
						{
							foreach ($value as $key2 => $value2) 
							{
								$errors->add('Workleave', $value2);
							}
						}
						else
						{
							$errors->add('Workleave', $value);
						}
					}
				}
				else
				{
					$attributes2['work_id'] 		= $value['workscalendars'][0]['id'];
					$attributes2['person_workleave_id'] 	= $is_success->data->id;
					$attributes2['created_by'] 		= Session::get('loggedUser');
					$attributes2['start'] 			= date('Y-m-d', strtotime($begin->format('Y-m-d')));
					$attributes2['end'] 			= date('Y-m-d', strtotime($end->format('Y-m-d'). ' - 1 day'));
					$attributes2['name'] 			= 'Cuti Bersama '.date('Y', strtotime($attributes2['start']));
					$attributes2['quota'] 			= 0 - (int)$total_l;
					$attributes2['status'] 			= 'CB';
					$attributes2['notes'] 			= 'Batch '.$attributes2['name'];

					$content 						= $this->dispatch(new Saving(new PersonWorkleave, $attributes2, null, new Person, $value['id']));
					$is_success_2 					= json_decode($content);

					if(!$is_success_2->meta->success)
					{
						foreach ($is_success_2->meta->errors as $key => $value) 
						{
							if(is_array($value))
							{
								foreach ($value as $key2 => $value2) 
								{
									$errors->add('Workleave', $value2);
								}
							}
							else
							{
								$errors->add('Workleave', $value);
							}
						}
					}
				}
			}

			if($key%10==0 && !$errors->count())
			{
				Session::put('batch.progress', 'Menyimpan '.$key.' dari '.count($persons));
			}

			unset($attributes1);
			unset($attributes2);
		}

		if($errors->count())
		{
			DB::rollback();
			Session::put('batch.error', 'Gagal menyimpan');
			Session::put('batch.errormsg', $errors);
		}
		else
		{
			DB::commit();
			Session::put('batch.success', 'Menyimpan Cuti untuk '.count($persons).' karyawan');
		}
	}

}
