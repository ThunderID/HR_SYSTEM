<?php namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

/* ----------------------------------------------------------------------
 * Document Model:
 * 	ID 								: Auto Increment, Integer, PK
 * 	calendar_id 					: Foreign Key From Calendar, Integer, Required
 * 	name 		 					: Required max 255
 * 	on 		 						: Required, Date
 * 	start 	 						: Required, Time
 * 	end		 						: Required, Time
 * 	status		 					: Required, enum presence_indoor, presence_outdoor, absence_workleave, absence_not_workleave
 *	created_at						: Timestamp
 * 	updated_at						: Timestamp
 * 	deleted_at						: Timestamp
 * 
/* ----------------------------------------------------------------------
 * Document Relationship :
 * 	//this package
 	1 Relationship belongsTo 
	{
		Calendar
	}

 * ---------------------------------------------------------------------- */

use Str, Validator, DateTime, Exception;

class Schedule extends BaseModel {

	use SoftDeletes;
	use \App\Models\Traits\BelongsTo\HasCalendarTrait;

	public 		$timestamps 		= 	true;

	protected 	$table 				= 	'schedules';

	protected 	$fillable			= 	[
											'name' 							,
											'on' 							,
											'start' 						,
											'end' 							,
											'status' 						,
										];

	protected 	$rules				= 	[
											'name'							=> 'required|max:255',
											'on'							=> 'required|date_format:"Y-m-d"',
											'start'							=> 'required|date_format:"H:i:s"',
											'end'							=> 'required|date_format:"H:i:s"',
											'status'						=> 'required|in:presence_indoor,presence_outdoor,absence_workleave,absence_not_workleave',
										];
										
	public $searchable 				= 	[
											'id' 							=> 'ID', 
											'calendarid' 					=> 'CalendarID', 
											'name' 							=> 'Name', 
											'ondate' 						=> 'OnDate', 
											'notid' 						=> 'NotID', 

											'branchid' 						=> 'BranchID', 
											'chartid' 						=> 'ChartID', 
											'withattributes' 				=> 'WithAttributes'
										];

	public $searchableScope 		= 	[
											'id' 							=> 'Could be array or integer', 
											'calendarid' 					=> 'Could be array or integer', 
											'ondate' 						=> 'Could be array or string (date)', 
											'name' 							=> 'Must be string', 
											'branchid' 						=> 'Could be array or integer', 
											'chartid' 						=> 'Could be array or integer', 
											'notid' 						=> 'Must be integer', 
											'withattributes' 				=> 'Must be array of relationship',
										];

	public $sortable 				= ['created_at', 'name'];

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

	public function scopeName($query, $variable)
	{
		return $query->where('name', 'like', '%'.$variable.'%');
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
				return $query->where('on', 'like', date('Y-m', strtotime($variable[0])).'%');
			}
			else
			{
				return $query->where('on', 'like', date('Y-m').'%');
			}
		}
		return $query->where('on', 'like', date('Y-m', strtotime($variable)).'%');
	}
}
