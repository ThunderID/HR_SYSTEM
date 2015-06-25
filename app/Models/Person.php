<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation id 	 			: Required, Foreign key Organisation, integer
 * 	uniqid 	 						: Varchar, 255, Required
 * 	name 	 						: Varchar, 255, Required
 * 	prefix_title 					: Varchar, 255, Required
 * 	suffix_title 					: Varchar, 255, Required
 * 	place_of_birth 					: Varchar, 255, Required
 * 	date_of_birth 					: Date, Y-m-d, Required
 * 	gender 							: Enum Female or Male, Required
 *	password						: Varchar, 255
 *	avatar							: 
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

use Str, Validator, DateTime, Exception;

class Person extends BaseModel {

	//use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\MorphMany\HasContactsTrait;
	use \App\Models\Traits\BelongsToMany\HasRelativesTrait;
	use \App\Models\Traits\BelongsToMany\HasDocumentsTrait;
	use \App\Models\Traits\HasMany\HasScheduleTrait;
	use \App\Models\Traits\HasMany\HasPersonWorkleavesTrait;
	use \App\Models\Traits\BelongsToMany\HasWorksOnTrait;
	use \App\Models\Traits\BelongsToMany\HasCalendarsTrait;
	use \App\Models\Traits\HasMany\HasWidgetsTrait;
	use \App\Models\Traits\HasMany\HasProcessLogsTrait;
	use \App\Models\Traits\HasOne\HasFingerTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'persons';

	protected 	$fillable			= 	[
											'uniqid' 						,
											'name' 							,
											'prefix_title' 					,
											'suffix_title' 					,
											'place_of_birth' 				,
											'date_of_birth' 				,
											'gender' 						,
											'password'						,
											'avatar'						,
										];

	protected	$dates 				= 	['created_at', 'updated_at', 'deleted_at'];

	protected 	$rules				= 	[
											'uniqid' 						=> 'required|max:255',
											'name' 							=> 'required|max:255',
											'prefix_title' 					=> 'max:255',
											'suffix_title' 					=> 'max:255',
											'place_of_birth' 				=> 'required|max:255',
											'date_of_birth' 				=> 'required|date_format:"Y-m-d"|before:tomorrow',
											'gender' 						=> 'required|in:female,male',
											'password'						=> 'max:255',
										];

	public $searchable 				= 	[
											'id' 							=> 'ID', 
											'notid' 						=> 'NotID', 
											'organisationid' 				=> 'OrganisationID', 
											
											'fullname' 						=> 'FullName', 
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
											'checkwork'	 					=> 'CheckWork',
											'currentwork' 					=> 'CurrentWork',
											'previouswork' 					=> 'PreviousWork',

											'displayupdatedfinger'			=> 'DisplayUpdatedFinger',

											'globalattendance'	 			=> 'GlobalAttendance',
										];


	public $searchableScope 		= 	[

											'id' 							=> 'Could be array or integer', 
											'notid' 						=> 'Must be integer', 
											'organisationid' 				=> 'Could be array or integer', 
											
											'fullname' 						=> 'Must be string', 
											'prefixtitle' 					=> 'Must be string', 
											'suffixtitle' 					=> 'Must be string', 
											'dateofbirth' 					=> 'Could be array or string (date)', 
											'gender' 						=> 'Must be male or female', 
											'checkcreate' 					=> 'Could be array or string (date)',
											'globalattendance'	 			=> 'Must be array of string and or case and or sort',
											'withattributes' 				=> 'Must be array of relationship',
											
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
											'checkwork'	 					=> 'Could be false or string (date)',
											'currentwork' 					=> 'Could be null or integer of ID',
											'previouswork' 					=> 'Null',

											'displayupdatedfinger'			=> 'Must be string (datetime)',
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

	public function scopeFullName($query, $variable)
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
		$query =  $query->selectraw('persons.*')
					->currentwork($variable['organisationid'])
					// ->globalattendancereport($variable);
					->selectraw('charts.name as position')
					->selectraw('charts.tag as department')
					->selectraw('sum(TIME_TO_SEC(schedule_start) - TIME_TO_SEC(schedule_end)) as possible_total_effective')
					->selectraw('sum(if(modified_status="HC" || modified_status="AS" || modified_status="UL" || modified_status="SS" || modified_status="SL", if(margin_start<0, abs(margin_start), 0) + if(margin_end<0, abs(margin_start), 0), if(actual_status="HC" || actual_status="AS" || actual_status="UL" || actual_status="SS" || actual_status="SL", if(margin_start<0, abs(margin_start), 0) + if(margin_end<0, abs(margin_start), 0), 0) )) as total_absence')
					// ->selectraw('avg(margin_start) as margin_start')
					// ->selectraw('avg(margin_end) as margin_end')
					// ->selectraw('avg(TIME_TO_SEC(start)) as avg_start')
					// ->selectraw('avg(TIME_TO_SEC(end)) as avg_end')
					// ->selectraw('avg(TIME_TO_SEC(fp_start)) as avg_fp_start')
					// ->selectraw('avg(TIME_TO_SEC(fp_end)) as avg_fp_end')
					// ->selectraw('avg(total_idle) as avg_idle')
					// ->selectraw('avg(total_idle_1) as avg_idle_1')
					// ->selectraw('avg(total_idle_2) as avg_idle_2')
					// ->selectraw('avg(total_idle_3) as avg_idle_3')
					// ->selectraw('avg(total_sleep) as avg_sleep')
					// ->selectraw('avg(total_active) as avg_active')
					// ->selectraw('sum(TIME_TO_SEC(start)) as start')
					// ->selectraw('sum(TIME_TO_SEC(end)) as end')
					// ->selectraw('sum(TIME_TO_SEC(fp_start)) as fp_start')
					// ->selectraw('sum(TIME_TO_SEC(fp_end)) as fp_end')
					// ->selectraw('sum(total_idle) as total_idle')
					->selectraw('sum(total_idle_1) as total_idle_1')
					->selectraw('sum(total_idle_2) as total_idle_2')
					->selectraw('sum(total_idle_3) as total_idle_3')
					// ->selectraw('sum(total_sleep) as total_sleep')
					->selectraw('sum(total_active) as total_active')
					->leftjoin('process_logs', 'process_logs.person_id', '=', 'persons.id')
					->leftjoin('works', 'process_logs.work_id', '=', 'works.id')
					->leftjoin('charts', 'works.chart_id', '=', 'charts.id');

		if(is_array($variable['on']))
		{
			if(!is_null($variable['on'][1]))
			{
				$query =  $query->where('on', '<=', date('Y-m-d', strtotime($variable['on'][1])))
							 ->where('on', '>=', date('Y-m-d', strtotime($variable['on'][0])));
			}
			elseif(!is_null($variable['on'][0]))
			{
				$query =  $query->where('on', '>=', date('Y-m-d', strtotime($variable['on'][0])));
			}
			else
			{
				$query =  $query->where('on', '>=', date('Y-m-d'));
			}
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

}
