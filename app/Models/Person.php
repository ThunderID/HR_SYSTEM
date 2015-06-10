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
	use \App\Models\Traits\BelongsToMany\HasRelativesTrait;
	use \App\Models\Traits\BelongsToMany\HasDocumentsTrait;
	use \App\Models\Traits\BelongsToMany\HasCalendarsTrait;
	use \App\Models\Traits\BelongsToMany\HasWorksOnTrait;
	use \App\Models\Traits\MorphMany\HasContactsTrait;
	use \App\Models\Traits\HasMany\HasScheduleTrait;
	use \App\Models\Traits\HasMany\HasProcessLogsTrait;
	use \App\Models\Traits\HasMany\HasPersonWorkleavesTrait;
	use \App\Models\Traits\HasMany\HasWidgetsTrait;
	use \App\Models\Traits\HasOne\HasFingerTrait;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;

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
											'organisationid' 				=> 'OrganisationID', 
											'fullname' 						=> 'FullName', 
											'prefixtitle' 					=> 'PrefixTitle', 
											'suffixtitle' 					=> 'SuffixTitle', 
											'dateofbirth' 					=> 'DateOfBirth', 
											'gender' 						=> 'Gender', 
											'withattributes' 				=> 'WithAttributes',
											'currentwork' 					=> 'CurrentWork',
											'currentcontact' 				=> 'CurrentContact',
											'email'			 				=> 'Email',
											'takenworkleave'				=> 'TakenWorkleave',
											'checktakenworkleave'			=> 'CheckTakenWorkleave',
											'defaultemail' 					=> 'DefaultEmail',
											'experiences' 					=> 'Experiences',
											'checkrelation' 				=> 'CheckRelation',
											'checkwork'	 					=> 'CheckWork',
											'checkresign'	 				=> 'CheckResign',
											'checkwidget'	 				=> 'CheckWidget',
											'checkapps'	 					=> 'CheckApps',
											'checkcreate' 					=> 'CheckCreate',
											'checkrelative' 				=> 'CheckRelative',
											'checkrelationof' 				=> 'CheckRelationOf',
											'checkworkleave' 				=> 'CheckWorkleave',
											'groupcontacts' 				=> 'GroupContacts',
											'charttag' 						=> 'ChartTag', 
											'branchname' 					=> 'BranchName', 
											'branchid' 						=> 'BranchID', 
											'chartid' 						=> 'ChartID', 
											'fullschedule' 					=> 'FullSchedule', 
											'displayupdatedfinger'			=> 'DisplayUpdatedFinger',
											'quotas'						=> 'Quotas',
											'minusquotas'					=> 'MinusQuotas',
											'workleaveid'					=> 'WorkleaveID',
											'requireddocuments'	 			=> 'RequiredDocuments',
											'globalattendance'	 			=> 'GlobalAttendance',
										];

	public $sortable 				= 	['name', 'prefix_title', 'suffix_title', 'date_of_birth', 'created_at', 'persons.created_at', 'persons.id'];
	
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
		return $query->where('persons.id', $variable);
	}

	public function scopeOrganisationID($query, $variable)
	{
		return $query->where('persons.organisation_id', $variable);
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
		$query =  $query->selectraw('hr_persons.*')
					->currentwork($variable['organisationid'])
					// ->globalattendancereport($variable);
					->selectraw('count(person_id) as total_attendance')
					->selectraw('avg(margin_start) as margin_start')
					->selectraw('avg(margin_end) as margin_end')
					->selectraw('avg(TIME_TO_SEC(start)) as avg_start')
					->selectraw('avg(TIME_TO_SEC(end)) as avg_end')
					->selectraw('avg(TIME_TO_SEC(fp_start)) as avg_fp_start')
					->selectraw('avg(TIME_TO_SEC(fp_end)) as avg_fp_end')
					->selectraw('avg(total_idle) as avg_idle')
					->selectraw('avg(total_sleep) as avg_sleep')
					->selectraw('avg(total_active) as avg_active')
					->selectraw('sum(TIME_TO_SEC(start)) as start')
					->selectraw('sum(TIME_TO_SEC(end)) as end')
					->selectraw('sum(TIME_TO_SEC(fp_start)) as fp_start')
					->selectraw('sum(TIME_TO_SEC(fp_end)) as fp_end')
					->selectraw('sum(total_idle) as total_idle')
					->selectraw('sum(total_sleep) as total_sleep')
					->selectraw('sum(total_active) as total_active')
					->leftjoin('process_logs', 'process_logs.person_id', '=', 'persons.id');
		
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

	public function scopeWithAttributes($query, $variable)
	{
		if(!is_array($variable))
		{
			$variable 			= [$variable];
		}

		return $query->with($variable);
	}

}
