<?php namespace App\Models;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	organisation_id 				: Foreign Key From Organisation, Integer, Required
 * 	import_from_id 					: Foreign Key From Calendar, Integer, Required
 * 	name 		 					: Required max 255
 * 	workdays 		 				: Text
 * 	start 		 					: Required time
 * 	end 		 					: Required time
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship hasMany 
	{
		Calendar
		Schedules
	}

 * 	//other package
 	1 Relationship belongsTo 
	{
		Calendar
		Organisation
	}

 * 	//other package
 	1 Relationship belongsToMany 
	{
		Charts
	}

 * ---------------------------------------------------------------------- */

use Illuminate\Database\Eloquent\SoftDeletes;
use Str, Validator, DateTime, Exception;

class Calendar extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasOrganisationTrait;
	use \App\Models\Traits\BelongsTo\HasCalendarTrait;
	use \App\Models\Traits\HasMany\HasSchedulesTrait;
	use \App\Models\Traits\HasMany\HasCalendarsTrait;
	use \App\Models\Traits\HasMany\HasWorksTrait;
	use \App\Models\Traits\BelongsToMany\HasChartsTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'tmp_calendars';
	
	protected 	$fillable			= 	[
											'import_from_id' 			,
											'name' 						,
											'workdays' 					,
											'start' 					,
											'end' 						,
										];

	protected 	$rules				= 	[
											// 'import_from_id'			=> 'exists:tmp_calendars,id',
											'name'						=> 'required|max:255',
											'start'						=> 'required|date_format:"H:i:s"',
											'end'						=> 'required|date_format:"H:i:s"',
										];

	public $searchable 				= 	[
											'id' 						=> 'ID', 
											'notid' 					=> 'NotID', 
											'organisationid' 			=> 'OrganisationID', 
											'parentid' 					=> 'ParentID', 
											'activeworks' 				=> 'ActiveWorks', 
											'name' 						=> 'Name', 
											'orname' 					=> 'OrName', 

											'starthour' 				=> 'StartHour', 
											'endhour' 					=> 'EndHour', 

											'branchid' 					=> 'BranchID', 
											'charttag' 					=> 'ChartTag', 
											
											'withattributes' 			=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 						=> 'Could be array or integer', 
											'notid' 					=> 'Must be integer', 
											'organisationid' 			=> 'Could be array or integer', 
											'parentid' 					=> 'Could be array or integer', 
											'activeworks' 				=> 'MUst be true', 
											'name' 						=> 'Must be string', 
											'orname' 					=> 'Could be array or string', 

											'starthour' 				=> 'Must be string', 
											'endhour' 					=> 'Must be string', 

											'branchid' 					=> 'Could be array or integer', 
											'charttag' 					=> 'Must be string', 
											
											'withattributes' 			=> 'Must be array of relationship',
										];

	public $sortable 				= 	['created_at', 'name'];

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
			return $query->whereIn('tmp_calendars.id', $variable);
		}
		return $query->where('tmp_calendars.id', $variable);
	}

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like', '%'.$variable.'%');
	}

	public function scopeOrName($query, $variable)
	{
		if(is_array($variable))
		{
			foreach ($variable as $key => $value) 
			{
				$query = $query->orwhere('name', 'like', '%'.$value.'%');
			}
			return $query;
		}
		return $query->where('name', 'like', '%'.$variable.'%');
	}

	public function scopeStartHour($query, $variable)
	{
		return $query->where('start', '=', $variable);
	}

	public function scopeEndHour($query, $variable)
	{
		return $query->where('end', '=', $variable);
	}
}
