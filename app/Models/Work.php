<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	chart_id 						: Foreign Key From Chart, Integer, Required if position and organisation not present
 * 	person_id 						: Foreign Key From Person, Integer, Required
 * 	calendar_id 					: Foreign Key From Calendar, Integer, Required
 * 	status 		 					: Enum contract or probation or internship or permanent or others or previous
 * 	grade 	 						: Varchar, 255
 * 	start 							: Date, Y-m-d, Required
 * 	end 							: Date, Y-m-d
 * 	position 						: required if chart_id not present
 * 	organisation 					: required if chart_id not present
 * 	reason_end_job 					: required if end present
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//other package
 	3 Relationships belongsTo 
	{
		Chart
		Person
		Calendar
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception, DB;

class Work extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasChartTrait;
	use \App\Models\Traits\BelongsTo\HasPersonTrait;
	use \App\Models\Traits\BelongsTo\HasCalendarTrait;
	use \App\Models\Traits\HasMany\HasWorkAuthenticationsTrait;
	use \App\Models\Traits\HasOne\HasFollowWorkleaveTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'works';

	protected 	$fillable			= 	[
											'calendar_id' 				,
											'chart_id' 					,
											'grade' 					,
											'status' 					,
											'start' 					,
											'end' 						,
											'position' 					,
											'organisation' 				,
											'reason_end_job' 			,
										];

	protected 	$rules				= 	[
											'chart_id'					=> 'required_without:position',
											'grade' 					=> 'max:255',
											'status' 					=> 'required|in:contract,probation,internship,permanent,others,admin,previous',
											'start' 					=> 'required|date_format:"Y-m-d"',
											'end' 						=> 'required_if:status,internship,previous|date_format:"Y-m-d"',
											'position' 					=> 'required_without:chart_id',
											'organisation' 				=> 'required_without:chart_id',
											'reason_end_job' 			=> 'required_with:end',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'calendarid' 				=> 'CalendarID', 
											'chartid' 					=> 'ChartID', 
											'personid' 					=> 'PersonID', 
											'organisationid' 			=> 'PersonOrganisationID', 
											'status' 					=> 'Status', 
											'active' 					=> 'Active', 
											'groupperson' 				=> 'GroupPerson', 
											'currentworkleave' 			=> 'CurrentWorkleave', 
											'withattributes' 			=> 'WithAttributes',
											'withtrashed' 				=> 'WithTrashed',
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'calendarid' 				=> 'Could be array or integer', 
											'chartid' 					=> 'Could be array or integer', 
											'personid' 					=> 'Could be array or integer', 
											'organisationid' 			=> 'Could be array or integer', 
											'status' 					=> 'Could be array or string', 
											'active' 					=> 'Must be enddate of work', 
											'groupperson' 				=> 'Must be true', 
											'currentworkleave' 			=> 'Must be true', 
											'withattributes' 			=> 'Must be array of relationship',
											'withtrashed' 				=> 'Must be true',
										];

	public $sortable 				= 	['created_at', 'start', 'end'];

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
	
	/* ---------------------------------------------------------------------------- FUNCTIONS -------------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- SCOPE -------------------------------------------------------------------------------*/

	public function scopeID($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('works.id', $variable);
		}
		return $query->where('works.id', $variable);
	}
	
	public function scopeStatus($query, $variable)
	{
		if(is_array($variable))
		{
			return $query->whereIn('status', $variable);
		}
		return $query->where('status', $variable);
	}

	public function scopeActive($query, $variable)
	{
		$bool 						= filter_var($variable, FILTER_VALIDATE_BOOLEAN);
		if($bool==true)
		{
			return $query->where(function($query){$query->where('end', '>=' ,date('Y-m-d'))->orWhereraw('end is null');});
		}

		return $query->whereHas('calendar', function($q){$q;});
	}

	public function scopeStartBefore($query, $variable)
	{
		return $query->where('start', '<=', date('Y-m-d', strtotime($variable)));
	}

	public function scopeGroupPerson($query, $variable)
	{
		return $query->groupBy('person_id');
	}

}
