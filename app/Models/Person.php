<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation id 	 			: Required, Foreign key Organisation, integer
 * 	uniqid 	 						: Varchar, 255, Required
 * 	username  						: Varchar, 255, Required
 * 	name 	 						: Varchar, 255, Required
 * 	prefix_title 					: Varchar, 255, Required
 * 	suffix_title 					: Varchar, 255, Required
 * 	place_of_birth 					: Varchar, 255, Required
 * 	date_of_birth 					: Date, Y-m-d, Required
 * 	gender 							: Enum Female or Male, Required
 *	password						: Varchar, 255
 *	avatar							: 
 * 	last_password_updated_at 		: Date, Y-m-d H:i:s, Required
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship belongsToMany 
	{
		Relatives
	}

	//other package
	3 Relationships belongsToMany 
	{
		Documents
		Works
		Calendars
	}

	3 Relationships hasMany 
	{
		Widgets
		Schedules
		PersonWorkleaves
	}

	1 Relationship morphMany 
	{
		Contacts
	}

	1 Relationship hasOne 
	{
		Finger
	}
 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Str, Validator, DateTime, Exception;

class Person extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\MorphMany\HasContactsTrait;
	use \App\Models\Traits\BelongsToMany\HasRelativesTrait;
	use \App\Models\Traits\BelongsToMany\HasDocumentsTrait;
	use \App\Models\Traits\HasMany\HasScheduleTrait;
	use \App\Models\Traits\HasMany\HasPersonWorkleavesTrait;
	use \App\Models\Traits\BelongsToMany\HasWorksOnTrait;
	use \App\Models\Traits\HasMany\HasWidgetsTrait;
	use \App\Models\Traits\HasMany\HasProcessLogsTrait;
	use \App\Models\Traits\HasMany\HasLogsTrait;
	use \App\Models\Traits\HasMany\HasMaritalStatusesTrait;
	use \App\Models\Traits\HasOne\HasFingerTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'persons';

	protected 	$fillable			= 	[
											'uniqid' 						,
											'username' 						,
											'name' 							,
											'prefix_title' 					,
											'suffix_title' 					,
											'place_of_birth' 				,
											'date_of_birth' 				,
											'gender' 						,
											'password'						,
											'avatar'						,
											'last_password_updated_at'		,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'uniqid' 						=> 'required|max:255',
											'username' 						=> 'max:255',
											'name' 							=> 'required|max:255',
											'prefix_title' 					=> 'max:255',
											'suffix_title' 					=> 'max:255',
											'place_of_birth' 				=> 'required|max:255',
											'date_of_birth' 				=> 'required|date_format:"Y-m-d"',
											'gender' 						=> 'required|in:female,male',
											'password'						=> 'max:255',
											'last_password_updated_at'		=> 'date_format:"Y-m-d H:i:s"|before:tomorrow',
										];

	public $searchable 				= 	[
											'id' 							=> 'ID', 
											'notid' 						=> 'NotID', 
											'organisationid' 				=> 'OrganisationID', 
											
											'name' 							=> 'Name', 
											'uniqid' 						=> 'Uniqid', 
											'username' 						=> 'Username', 
											'prefixtitle' 					=> 'PrefixTitle', 
											'suffixtitle' 					=> 'SuffixTitle', 
											'dateofbirth' 					=> 'DateOfBirth', 
											'gender' 						=> 'Gender', 
											'checkcreate' 					=> 'CheckCreate',
											'withattributes' 				=> 'WithAttributes',
											
											'contactid' 					=> 'ContactID',
											'defaultcontact' 				=> 'DefaultContact',
											'email'			 				=> 'Email',
											'defaultemail' 					=> 'DefaultEmail',

											'checkrelative' 				=> 'CheckRelative',
											'checkrelation' 				=> 'CheckRelation',
											'checkrelationof' 				=> 'CheckRelationOf',
											
											'personworkleaveid'				=> 'PersonWorkleaveID',
											'takenworkleave'				=> 'TakenWorkleave',
											'checktakenworkleave'			=> 'CheckTakenWorkleave',
											'minusquotas'					=> 'MinusQuotas',
											'fullschedule' 					=> 'FullSchedule', 

											'workleaveid'					=> 'WorkleaveID',
											'checkworkleave' 				=> 'CheckWorkleave',
											'quotas'						=> 'Quotas',

											'branchid' 						=> 'BranchID', 
											'chartid' 						=> 'ChartID', 
											'charttag' 						=> 'ChartTag', 
											'chartchild' 					=> 'ChartChild', 
											'chartnotadmin' 				=> 'ChartNotAdmin', 
											'checkwork'	 					=> 'CheckWork',
											'currentwork' 					=> 'CurrentWork',
											'previouswork' 					=> 'PreviousWork',

											'maritalstatus' 				=> 'MaritalStatus',
											'workcalendar' 					=> 'WorkCalendar',
											'withworkcalendarschedules' 	=> 'WithWorkCalendarSchedules',

											'displayupdatedfinger'			=> 'DisplayUpdatedFinger',

											'globalattendance'	 			=> 'GlobalAttendance',
											'globalactivity'	 			=> 'GlobalActivity',
											'globalworkleave'	 			=> 'GlobalWorkleave',
											'globalsanction'	 			=> 'GlobalSanction',
											
											'processlogattendancesondate'	=> 'ProcessLogAttendancesOndate',
											'processlogidlesondate'			=> 'ProcessLogIdlesOndate',
											'logsondate'	 				=> 'LogsOndate',
											'withtrashed' 					=> 'WithTrashed',
										];


	public $searchableScope 		= 	[

											'id' 							=> 'Could be array or integer', 
											'notid' 						=> 'Must be integer', 
											'organisationid' 				=> 'Could be array or integer', 
											
											'name' 							=> 'Must be string', 
											'uniqid' 						=> 'Must be string', 
											'username' 						=> 'Must be string', 
											'prefixtitle' 					=> 'Must be string', 
											'suffixtitle' 					=> 'Must be string', 
											'dateofbirth' 					=> 'Could be array or string (date)', 
											'gender' 						=> 'Must be male or female', 
											'checkcreate' 					=> 'Could be array or string (date)',
											'globalattendance'	 			=> 'Must be array of string and or case and or sort',
											'globalactivity'	 			=> 'Must be array of string and or case and or sort',
											'globalworkleave'	 			=> 'Must be array of string and or case and or sort',
											'globalsanction'	 			=> 'Must be array of string and or case',
											
											'withattributes' 				=> 'Must be array of relationship',
											
											'maritalstatus' 				=> 'Must be true',
											
											'contactid' 					=> 'ID of contact',
											'defaultcontact' 				=> 'Must be true',
											'email'			 				=> 'Must be string',
											'defaultemail' 					=> 'Must be true',

											'checkrelative' 				=> 'Null',
											'checkrelation' 				=> 'Must be integer of ID',
											'checkrelationof' 				=> 'Must be integer of ID',
											
											'takenworkleave'				=> 'Must be array of : status and on (could be array or string (date))',
											'checktakenworkleave'			=> 'Must be array of : status and on (could be array or string (date))',
											'minusquotas'					=> 'Must be array of : ids (array of integer ID) and ondate (array of string (date))',
											'fullschedule' 					=> 'Must be string (date)', 

											'workleaveid'					=> 'Could be array or integer',
											'checkworkleave' 				=> 'Could be false or array or string (time)',
											'quotas'						=> 'Must be array of ondate (array of string(time))',

											'branchid' 						=> 'Could be array or integer', 
											'chartid' 						=> 'Could be array or integer', 
											'charttag' 						=> 'Must be string', 
											'chartchild' 					=> 'Must be string of path', 
											'chartnotadmin' 				=> 'Must be string of path', 
											'checkwork'	 					=> 'Could be false or string (date)',
											'currentwork' 					=> 'Could be null or integer of ID',
											'previouswork' 					=> 'Null',

											'workcalendar' 					=> 'Need to be true',
											'withworkcalendarschedules' 	=> 'Need to be true',

											'displayupdatedfinger'			=> 'Must be string (datetime)',

											'processlogsondate'	 			=> 'Could be array or string (date)',
											'logsondate'	 				=> 'Could be array or string (date)',
											'withtrashed' 					=> 'Must be true',
										];

	public $sortable 				= 	['name', 'prefix_title', 'suffix_title', 'date_of_birth', 'created_at', 'persons.created_at', 'persons.id', 'persons.name'];
	
	protected $appends				= 	['has_relatives', 'has_works', 'has_contacts', 'log_notes'];

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

	/* ---------------------------------------------------------------------------- ERRORS ----------------------------------------------------------------------------*/
	/**
	 * return errors
	 *
	 * @return MessageBag
	 * @author 
	 **/
	function getError()
	{
		return $this->errors;
	}

	/* ---------------------------------------------------------------------------- QUERY BUILDER ---------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ---------------------------------------------------------------------------------*/

	/* ---------------------------------------------------------------------------- ACCESSOR --------------------------------------------------------------------------------*/

	public function getHasRelativesAttribute($value)
	{
		if(isset($this->getRelations()['relatives']) && count($this->getRelations()['relatives']))
		{
			return true;
		}
		return false;
	}

	public function getHasWorksAttribute($value)
	{
		if(isset($this->getRelations()['works']) && count($this->getRelations()['works']))
		{
			return true;
		}
		return false;
	}

	public function getHasContactsAttribute($value)
	{
		if(isset($this->getRelations()['contacts']) && count($this->getRelations()['contacts']))
		{
			return true;
		}
		return false;
	}
	
	public function getLogNotesAttribute($value)
	{
		if(isset($this['attributes']['margin_start']))
		{
			$notes 			= [];
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
		return null;
	}
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('persons.id', $variable);
		}
		return $query->where('persons.id', $variable);
	}
	
	public function scopeUniqid($query, $variable)
	{
		return $query->where('uniqid', $variable);
	}

	public function scopeUsername($query, $variable)
	{
		return $query->where('username', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like' ,'%'.$variable.'%');
	}

	public function scopePrefixTitle($query, $variable)
	{
		return $query->where('prefix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeSuffixTitle($query, $variable)
	{
		return $query->where('suffix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeOrPrefixTitle($query, $variable)
	{
		return $query->orwhere('prefix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeOrSuffixTitle($query, $variable)
	{
		return $query->orwhere('suffix_title', 'like' ,'%'.$variable.'%');
	}

	public function scopeDateOfBirth($query, $variable)
	{
		if(is_array($variable) && count($variable)==2)
		{
			return $query->where('date_of_birth', '>' , $variable[0])->where('date_of_birth', '<=' , $variable[1]);
		}

		return $query->where('date_of_birth', '>' , $variable);
	}

	public function scopeGender($query, $variable)
	{
		return $query->where('gender', $variable);
	}

	public function scopeGlobalAttendance($query, $variable)
	{
		if(isset($variable['on']) && is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable['on']))
		{
			$start 	= date('Y-m-d', strtotime($variable['on']));
			$end 	= date('Y-m-d', strtotime($variable['on']));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}

		$query =  $query->selectraw('persons.*')
					->wherehas('works', function($q)use($end){$q->where(DB::raw('`end` >= '. $end));})
					->wherehas('workscalendars', function($q)use($variable){$q->status(['permanent', 'contract', 'probation', 'internship', 'permanent','others']);})
					->with(['works' => function($q)use($end){$q->where(DB::raw('`end` >= '. $end));}])
					->with(['works.branch'])
					->selectraw('branches.name as branch')
					->selectraw('charts.name as position')
					->selectraw('charts.tag as department')
					->selectraw('sum(TIME_TO_SEC(schedule_end) - TIME_TO_SEC(schedule_start)) as possible_total_effective')
					->leftjoin('process_logs', function ($join) use($start, $end) {
							$join->on('persons.id', '=', 'process_logs.person_id')
								->where('process_logs.on', '>=', $start)
								->where('process_logs.on', '<=', $end)
								->wherenull('process_logs.deleted_at')
							;
						})
					->selectraw('sum((SELECT if(actual_status="HB",1,0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as HB')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HT", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as HT')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HP", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as HP')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HD", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as HD')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HC", 1, if(modified_status="", 1, 0)),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as HC')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="DN", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as DN')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="SS", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as SS')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="SL", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as SL')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="CN", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as CN')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="CB", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as CB')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="CI", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as CI')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="UL", 1, 0),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as UL')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="AS", 1, if(modified_status="", 1, 0)),0) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as `AS`')
					->selectraw('sum((SELECT if(modified_status="HC" || modified_status="AS" || modified_status="UL" || modified_status="SS" || modified_status="SL", if(margin_start<0, abs(margin_start), 0) + if(margin_end<0, abs(margin_end), 0), if(modified_status=0, if(actual_status="HC" || actual_status="AS" || actual_status="UL" || actual_status="SS" || actual_status="SL", if(margin_start<0, abs(margin_start), 0) + if(margin_end<0, abs(margin_end), 0), 0),0) ) FROM attendance_logs WHERE attendance_logs.process_log_id = process_logs.id  and actual_status <> "L" and attendance_logs.deleted_at is null order by updated_at desc limit 1)) as total_absence')
					->leftjoin('works', 'process_logs.work_id', '=', 'works.id')
					->leftjoin('charts', 'works.chart_id', '=', 'charts.id')
					->leftjoin('branches', 'charts.branch_id', '=', 'branches.id')
					;


		if(isset($variable['name']))
		{
			$query =  $query->where('persons.name', 'like', '%'.$variable['name'].'%');
		}

		if(isset($variable['branchid']))
		{
			$query =  $query->where('branches.id', $variable['branchid']);
		}

		if(isset($variable['chartchild']))
		{
			if(is_array($variable['chartchild']))
			{
				$query =  $query->wherehas('works', function($q)use($variable){$q->where(function($q)use($variable){$q->child($variable['chartchild'][0])->orwhere('works.id', $variable['chartchild'][1]);});});
			}
			else
			{
				$query =  $query->wherehas('works', function($q)use($variable){$q->child($variable['chartchild']);});
			}
		}

		if(isset($variable['charttag']))
		{
			$query =  $query->where('charts.tag', $variable['charttag']);
		}

		if(isset($variable['chartid']))
		{
			$query =  $query->where('charts.id', $variable['chartid']);
		}

		if(isset($variable['case']))
		{
			switch ($variable['case']) 
			{
				case 'late':
					$query = $query->where('margin_start', '<', 0);
					break;
				case 'ontime':
					$query = $query->where('margin_start', '>=', 0);
					break;
				
				case 'earlier':
					$query = $query->where('margin_end', '<', 0);
					break;
				case 'overtime':
					$query = $query->where('margin_start', '>', 0);
					break;
				default:
					$query;
					break;
			}
		}

		if(isset($variable['sort']))
		{
			$query = $query->orderBy($variable['sort'][0], $variable['sort'][1]);
		}

		return $query->groupBy('persons.id')
					;
	}

	public function scopeGlobalActivity($query, $variable)
	{
		if(isset($variable['on']) && is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable['on']))
		{
			$start 	= date('Y-m-d', strtotime($variable['on']));
			$end 	= date('Y-m-d', strtotime($variable['on']));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}

		$query =  $query->selectraw('persons.*')
					->wherehas('works', function($q)use($end){$q->where(DB::raw('`end` >= '. $end));})
					->wherehas('workscalendars', function($q)use($variable){$q->status(['permanent', 'contract', 'probation', 'internship', 'permanent','others']);})
					->with(['works' => function($q)use($end){$q->where(DB::raw('`end` >= '. $end));}])
					->with(['works.branch'])
					->selectraw('branches.name as branch')
					->selectraw('charts.name as position')
					->selectraw('charts.tag as department')
					->selectraw('sum(if(schedule_start="00:00:00",0,1)) as total_days')

					->selectraw('sum((SELECT total_idle_1 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_idle_1')
					->selectraw('sum((SELECT total_idle_2 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_idle_2')
					->selectraw('sum((SELECT total_idle_3 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_idle_3')
					->selectraw('sum((SELECT total_idle FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_idle')
					->selectraw('sum((SELECT frequency_idle_1 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as frequency_idle_1')
					->selectraw('sum((SELECT frequency_idle_2 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as frequency_idle_2')
					->selectraw('sum((SELECT frequency_idle_3 FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as frequency_idle_3')
					->selectraw('sum((SELECT total_active FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_active')
					->selectraw('sum((SELECT total_active + total_idle FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) as total_presence')
					->leftjoin('process_logs', function ($join) use($start, $end) {
							$join->on('persons.id', '=', 'process_logs.person_id')
								->where('process_logs.on', '>=', $start)
								->where('process_logs.on', '<=', $end)
								->wherenull('process_logs.deleted_at')
							;
						})
					->leftjoin('works', 'process_logs.work_id', '=', 'works.id')
					->leftjoin('charts', 'works.chart_id', '=', 'charts.id')
					->leftjoin('branches', 'charts.branch_id', '=', 'branches.id')
					;

		if(isset($variable['name']))
		{
			$query =  $query->where('persons.name', 'like', '%'.$variable['name'].'%');
		}

		if(isset($variable['branchid']))
		{
			$query =  $query->where('branches.id', $variable['branchid']);
		}
		
		if(isset($variable['chartchild']))
		{
			if(is_array($variable['chartchild']))
			{
				$query =  $query->wherehas('works', function($q)use($variable){$q->where(function($q)use($variable){$q->child($variable['chartchild'][0])->orwhere('works.id', $variable['chartchild'][1]);});});
			}
			else
			{
				$query =  $query->wherehas('works', function($q)use($variable){$q->child($variable['chartchild']);});
			}
		}

		if(isset($variable['charttag']))
		{
			$query =  $query->where('charts.tag', $variable['charttag']);
		}

		if(isset($variable['chartid']))
		{
			$query =  $query->where('charts.id', $variable['chartid']);
		}
		if(isset($variable['case']))
		{
			switch ($variable['case']) 
			{
				case 'late':
					$query = $query->where('margin_start', '<', 0);
					break;
				case 'ontime':
					$query = $query->where('margin_start', '>=', 0);
					break;
				
				case 'earlier':
					$query = $query->where('margin_end', '<', 0);
					break;
				case 'overtime':
					$query = $query->where('margin_start', '>', 0);
					break;
				default:
					$query;
					break;
			}
		}

		if(isset($variable['sort']))
		{
			switch ($variable['sort'][0]) 
			{
				case 'total_idle':
					$query = $query->orderByRaw(DB::raw('sum((SELECT total_idle FROM idle_logs WHERE idle_logs.process_log_id = process_logs.id and idle_logs.deleted_at is null order by updated_at desc limit 1)) '. $variable['sort'][1]));
					break;
				default:
						$query = $query->orderBy($variable['sort'][0], $variable['sort'][1]);
					break;
			}
		}
		return $query->groupBy('persons.id')
					;
	}

	public function scopeGlobalWorkleave($query, $variable)
	{
		if(isset($variable['on']) && is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable['on']))
		{
			$start 	= date('Y-m-d', strtotime($variable['on']));
			$end 	= date('Y-m-d', strtotime($variable['on']));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}
		
		$query =  $query->selectraw('persons.*')
					->wherehas('works', function($q)use($start){$q->wherenull('end')->orwhere('end', '>=',  $start);})
					->with(['works' => function($q)use($start){$q->wherenull('end')->orwhere('end', '>=',  $start);}])
					->selectraw('(SELECT sum(if(person_workleaves.status="CN", if(person_workleaves.quota>0, person_workleaves.quota, 0), 0)) FROM person_workleaves WHERE person_workleaves.person_id = persons.id and date_format(date(person_workleaves.start),"%Y-%m-%d") >= '.$start.' and date_format(date(person_workleaves.end),"%Y-%m-%d") >= '.$end.' and deleted_at is null) as quotas')
					->selectraw('(SELECT sum(if(person_workleaves.status="CI", if(person_workleaves.quota>0, person_workleaves.quota, 0), 0)) FROM person_workleaves WHERE person_workleaves.person_id = persons.id and date_format(date(person_workleaves.start),"%Y-%m-%d") >= '.$start.' and date_format(date(person_workleaves.end),"%Y-%m-%d") >= '.$end.' and deleted_at is null) as plus_quotas')
					->selectraw('(SELECT sum(if(person_workleaves.quota<0, abs(person_workleaves.quota), 0)) FROM person_workleaves WHERE person_workleaves.person_id = persons.id and date_format(date(person_workleaves.start),"%Y-%m-%d") >= '.$start.' and date_format(date(person_workleaves.end),"%Y-%m-%d") >= '.$end.' and deleted_at is null) as minus_quotas')
						;

		if(isset($variable['name']))
		{
			$query =  $query->where('persons.name', 'like', '%'.$variable['name'].'%');
		}

		if(isset($variable['branchid']))
		{
			$query =  $query->where('branches.id', $variable['branchid']);
		}

		if(isset($variable['chartchild']))
		{
			$query =  $query->where('charts.path', 'like', $variable.'%');
		}

		if(isset($variable['charttag']))
		{
			$query =  $query->where('charts.tag', $variable['charttag']);
		}

		if(isset($variable['chartid']))
		{
			$query =  $query->where('charts.id', $variable['chartid']);
		}

		if(isset($variable['case']))
		{
			switch ($variable['case']) 
			{
				case 'late':
					$query = $query->where('margin_start', '<', 0);
					break;
				case 'ontime':
					$query = $query->where('margin_start', '>=', 0);
					break;
				
				case 'earlier':
					$query = $query->where('margin_end', '<', 0);
					break;
				case 'overtime':
					$query = $query->where('margin_start', '>', 0);
					break;
				default:
					$query;
					break;
			}
		}

		if(isset($variable['sort']))
		{
			$query = $query->orderBy($variable['sort'][0], $variable['sort'][1]);
		}

		return $query->groupBy('persons.id')
					;
	}

	public function scopeCheckCreate($query, $variable)
	{
		if(!is_array($variable))
		{
			$days 				= new DateTime($variable);

			return $query->where('created_at', '>=', $days->format('Y-m-d'));
		}
		return $query->where('created_at', '>=', $variable[0])
					->where('created_at', '<=', $variable[1]);
	}

	public function scopeGlobalSanction($query, $variable)
	{
		if(isset($variable['on']) && is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$start 	= date('Y-m-d', strtotime($variable['on'][0]));
				$end 	= date('Y-m-d', strtotime($variable['on'][1]));
			}
			else
			{
				$start 	=   date('Y-m-d');
				$end 	=   date('Y-m-d');
			}
		}
		elseif(isset($variable['on']))
		{
			$start 	= date('Y-m-d', strtotime($variable['on']));
			$end 	= date('Y-m-d', strtotime($variable['on']));
		}
		else
		{
			$start 	=   date('Y-m-d');
			$end 	=   date('Y-m-d');
		}

		$query =  $query->selectraw('persons.*')
					->wheredoesnthave('processlogs.attendancelogs.attendancedetails', function($q){$q;})
					->wherehas('processlogs.attendancelogs', function($q){$q->wherenotin('actual_status', ['HB', 'L'])->wherenotin('modified_status', ['HD', 'SS', 'SL', 'CN', 'CB', 'CI', 'DN', 'L']);})
					->join('process_logs', function ($join) use($start, $end) {
							$join->on('persons.id', '=', 'process_logs.person_id')
								->where('process_logs.on', '>=', $start)
								->where('process_logs.on', '<=', $end)
								->wherenull('process_logs.deleted_at')
							;
						})
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HT", 1, 0),0) FROM attendance_logs left join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" and attendance_details.attendance_log_id is null order by attendance_logs.updated_at desc limit 1)) as HT')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HP", 1, 0),0) FROM attendance_logs left join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" and attendance_details.attendance_log_id is null order by attendance_logs.updated_at desc limit 1)) as HP')
					->selectraw('sum((SELECT if(actual_status="HC",if(modified_status="HC", 1, if(modified_status="", 1, 0)),0) FROM attendance_logs left join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" AND attendance_details.attendance_log_id is null order by attendance_logs.updated_at desc limit 1)) as HC')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="UL", 1, 0),0) FROM attendance_logs left join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" and attendance_details.attendance_log_id is null order by attendance_logs.updated_at desc limit 1)) as UL')
					->selectraw('sum((SELECT if(actual_status="AS",if(modified_status="AS", 1, if(modified_status="", 1, 0)),0) FROM attendance_logs left join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" AND attendance_details.attendance_log_id is null order by attendance_logs.updated_at desc limit 1)) as `AS`')
					->selectraw('count((SELECT attendance_details.attendance_log_id FROM attendance_logs join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" AND attendance_details.attendance_log_id is null and attendance_details.person_workleave_id <> 0 order by attendance_logs.updated_at desc limit 1)) as lastwl')
					->selectraw('count((SELECT attendance_details.attendance_log_id FROM attendance_logs join attendance_details on attendance_logs.id = attendance_details.attendance_log_id WHERE attendance_logs.process_log_id = process_logs.id  and attendance_logs.deleted_at is null and attendance_details.deleted_at is null and actual_status <> "L" AND attendance_details.attendance_log_id is null and attendance_details.person_document_id <> 0 order by attendance_logs.updated_at desc limit 1)) as lastdoc')
					;

		return $query->groupBy('persons.id')
					;
	}
}
