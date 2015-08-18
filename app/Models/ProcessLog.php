<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	work_id 						: Foreign Key From Work, Integer, Required
 * 	name 		 					: Required max 255
 * 	on 		 						: Required, Date
 * 	start 		 					: Required, Time
 * 	end 		 					: Time
 * 	fp_start 	 					: Time
 * 	fp_end 		 					: Time
 * 	schedule_start 		 			: Required, Time
 * 	schedule_end 		 			: Required, Time
 * 	tooltip 	 					: 
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	2 Relationships belongsTo 
	{
		Person
		Work
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Str, Validator, DateTime, Exception;

class ProcessLog extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\BelongsTo\HasWorkTrait;
	use \App\Models\Traits\HasMany\HasAttendanceLogsTrait;
	use \App\Models\Traits\HasMany\HasIdleLogsTrait;

	public 		$timestamps 		= true;

	protected 	$table 				= 	'process_logs';
	
	protected 	$fillable			= 	[
											'name' 							,
											'on' 							,
											'start' 						,
											'end' 							,
											'fp_start' 						,
											'fp_end' 						,
											'schedule_start' 				,
											'schedule_end' 					,
											'tooltip' 						,
										];

	protected 	$rules				= 	[
											'name'						=> 'required|max:255',
											'on'						=> 'required|date_format:"Y-m-d"',
											'start'						=> 'date_format:"H:i:s"',
											'end'						=> 'date_format:"H:i:s"',
											'fp_start'					=> 'date_format:"H:i:s"',
											'fp_end'					=> 'date_format:"H:i:s"',
											'schedule_start'			=> 'required|date_format:"H:i:s"',
											'schedule_end'				=> 'required|date_format:"H:i:s"',
											
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'personid' 					=> 'PersonID', 

											// 'organisationid' 			=> 'OrganisationID', 
											'branchid' 					=> 'BranchID', 
											'charttag' 					=> 'ChartTag', 
											
											'lastattendancelog' 		=> 'LastAttendanceLog', 
											'modifiedstatus' 			=> 'ModifiedStatus', 
											'ondate' 					=> 'OnDate', 
											'global' 					=> 'Global', 
											'local' 					=> 'Local', 
											'orderworkhour' 			=> 'OrderWorkHour', 
											'orderavgworkhour' 			=> 'OrderAverageWorkHour', 
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											// 'organisationid' 			=> 'Could be array or integer', 
											'lastattendancelog' 		=> 'Null', 
											'branchid' 					=> 'Could be array or integer', 
											'charttag' 					=> 'Must be string', 
											'modifiedstatus' 			=> 'Could be array or string', 
											'ondate' 					=> 'Could be array or string (date)', 
											'global' 					=> 'Null', 
											'local' 					=> 'Null', 
											'orderworkhour' 			=> 'Null', 
											'orderavgworkhour' 			=> 'Null', 
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'on', 'margin_start', 'margin_end', 'total_idle', 'person_id', 'total_active', 'total_sleep'];

	protected $appends				= 	['has_schedule', 'notes'];

	/* ---------------------------------------------------------------------------- CONSTRUCT ----------------------------------------------------------------------------*/
	/**
	 * boot
	 *
	 * @return void
	 * @author 
	 **/
	static function boot()
	{
		parent::boot();

		Static::saving(function($data)
		{
			$validator = Validator::make($data->toArray(), $data->rules);

			if ($validator->passes())
			{
				return true;
			}
			else
			{
				$data->errors = $validator->errors();
				return false;
			}
		});
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/
	public function getHasScheduleAttribute($value)
    {
    	if(date("H:i:s", strtotime($this->schedule_start))==date('H:i:s', strtotime('00:00:00')) && date("H:i:s", strtotime($this->schedule_end))==date('H:i:s', strtotime('00:00:00')))
    	{
    		return false;
    	}
		return true;
    }

	public function getNotesAttribute($value)
    {
		if($this->margin_start < 0)
		{
			$notes[] = 'late';
		}
		elseif($this->margin_start >= 0)
		{
			$notes[] = 'ontime';
		}

		if($this->margin_end < 0)
		{
			$notes[] = 'earlier';
		}
		elseif($this->margin_end > 3600)
		{
			$notes[] = 'overtime';
		}
		elseif($this->margin_end <= 3600 && $this->margin_end >= 0)
		{
			$notes[] = 'ontime';
		}

		return $notes;
	}

	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('process_logs.id', $variable);
		}
		return $query->where('process_logs.id', $variable);
	}

	public function scopeModifiedStatus($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('process_logs.modified_status', $variable);
		}
		return $query->where('process_logs.modified_status', $variable);
	}
	
	public function scopeOnDate($query, $variable)
	{
		if(is_array($variable))
		{
			if(!is_null($variable[1]))
			{
				return $query->where('on', '<=', date('Y-m-d', strtotime($variable[1])))
							 ->where('on', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			elseif(!is_null($variable[0]))
			{
				return $query->where('on', '>=', date('Y-m-d', strtotime($variable[0])));
			}
			else
			{
				return $query->where('on', '>=', date('Y-m-d'));
			}
		}
		return $query->where('on', '>=', date('Y-m-d', strtotime($variable)));
	}

	public function scopeLate($query, $variable)
	{
		return $query->where('margin_start', '<', 0);
	}

	public function scopeOnTime($query, $variable)
	{
		return $query->where('margin_start', '>=', 0);
	}

	public function scopeEarlier($query, $variable)
	{
		return $query->where('margin_end', '<', 0);
	}

	public function scopeOvertime($query, $variable)
	{
		return $query->where('margin_end', '>', 0);
	}

	public function scopeGlobal($query, $variable)
	{
		return $query->select(['person_id'])
					->selectRaw('avg(TIME_TO_SEC(schedule_start)) as avg_schedule_start')
					->selectRaw('avg(TIME_TO_SEC(schedule_end)) as avg_schedule_end')
					->selectRaw('avg(margin_start) as margin_start')
					->selectRaw('avg(margin_end) as margin_end')

					->selectRaw('avg(TIME_TO_SEC(start)) as avg_start')
					->selectRaw('sum(TIME_TO_SEC(start)) as start')
					->selectRaw('avg(TIME_TO_SEC(end)) as avg_end')
					->selectRaw('sum(TIME_TO_SEC(end)) as end')

					->selectRaw('avg(TIME_TO_SEC(fp_start)) as avg_fp_start')
					->selectRaw('sum(TIME_TO_SEC(fp_start)) as fp_start')
					->selectRaw('avg(TIME_TO_SEC(fp_end)) as avg_fp_end')
					->selectRaw('sum(TIME_TO_SEC(fp_end)) as fp_end')

					->selectRaw('sum(total_idle) as total_idle')
					->selectRaw('avg(total_idle) as avg_idle')
					->selectRaw('sum(total_sleep) as total_sleep')
					->selectRaw('avg(total_sleep) as avg_sleep')
					->selectRaw('sum(total_active) as total_active')
					->selectRaw('avg(total_active) as avg_active')
					->groupBy('person_id')
		;

	}

	public function scopeLocal($query, $variable)
	{
		return $query->selectRaw('*')
					->selectRaw('(TIME_TO_SEC(end)) - (TIME_TO_SEC(start)) as total_workhour');
	}

	public function scopeOrderWorkHour($query, $variable)
	{
		return $query->orderBy(DB::Raw('sum(TIME_TO_SEC(end)) - sum(TIME_TO_SEC(start))'), $variable);
	}

	public function scopeOrderAverageWorkHour($query, $variable)
	{
		return $query->orderBy(DB::Raw('avg(TIME_TO_SEC(end)) - avg(TIME_TO_SEC(start))'), $variable);
	}
}
